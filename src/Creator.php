<?php

namespace AcfCreator;

class Creator extends Base
{
    public function __construct()
    {
        $this->fields = [];
        $this->currentField = [];
        $this->nestedFieldStack = [];
    }


    /**
     * the count of fields
     * @return int
     */
    public function count()
    {
        return count($this->fields);
    }

    /**
     * returns field by it name example (new AcfCreate())->getField('title')
     * @param string $name
     * @return array
     */
    protected function getField(string $name, $recursiveFields = [])
    {
        $count = !empty($recursiveFields) ? count($recursiveFields) : $this->count();
        $fields = $recursiveFields ?: $this->fields;
        if (empty($name) || $count == 0) {
            return [];
        } else {
            $fieldResult = [];
            for ($i = 0; $i < $count; $i++) {
                $field = $fields[$i];
                if (!empty($field['name']) && $field['name'] === $name) {
                    $fieldResult = $field;
                    break;
                } else if (!empty($field['sub_fields'])) {
                    $fieldResult = $this->getField($name, $field['sub_fields']);
                }
            }
            return $fieldResult;
        }
    }

    /**
     * returns all fields
     * @return array
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * returns fields in json format
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->fields, JSON_PRETTY_PRINT);
    }

    /**
     * returns the current field
     * @param mixed $recursiveFields
     * @return mixed
     */
    public function getCurrentField($recursiveFields = [])
    {
        if (empty($this->currentField)) {

            $data = $recursiveFields ?: $this->fields[count($this->fields) - 1];
            if ($this->isWithSubFields($data)) {
                foreach ($data as  $value) {
                    if ($this->isWithSubFields($value)) {
                        $this->getCurrentField($value);
                    }
                }
                $data = $data[count($data) - 1];
            }

            return $data;
        }

        return $this->currentField;
    }

    /**
     * merge fields to current created fields example $acfCreateinstance->merge($fields)
     * @param array $fields
     * @return self
     */
    public  function merge(array $fields = [], &$nestedFieldStack = [])
    {
        if (empty($fields)) {
            return $this;
        }

        $currentFields = &$this->fields;
        $currentField = $this->getCurrentField();
        $defaultNestedStack = $this->nestedFieldStack;
        $nestedStack = !empty($nestedFieldStack) ? $nestedFieldStack : $defaultNestedStack;

        if ($this->isWithSubFields($currentField) && !empty($nestedStack)) {
            foreach ($nestedStack as  &$nestedValue) {
                if (!empty($nestedValue['name']) && $nestedValue['name'] == $currentField['name']) {
                    $nestedValue['sub_fields'][] = $fields;
                } else if (!empty($nestedValue['sub_fields'])) {
                    $this->merge($fields, $nestedValue);
                } else {
                    $nestedStack[] = $fields;
                }
            }
        } else if (!empty($currentFields)) {
            $currentFields[] = $fields;
        }

        return $this;
    }

    /**
     * Summary of isWithSubFields
     * @param array $field
     * @return bool
     */
    protected function isWithSubFields(array $field = [])
    {
        return !empty($field['type']) && in_array($field['type'], [self::REPEATER, self::GROUP]);
    }


    /**
     * Remove a field by name from the fields array.
     * This method will recursively search for the field in nested repeaters or groups.
     * @param string $fieldName
     * @return self
     */
    public  function remove(string $fieldName)
    {
        if (empty($fieldName)) {
            return $this;
        }
        $this->fields = $this->recursiveRemove($this->fields, $fieldName);
        return $this;
    }

    /**
     * Recursive function to remove a field by name from the provided array of fields.
     * @param array $fields
     * @param string $fieldName
     * @return array
     */
    private function recursiveRemove(array $fields, string $fieldName): array
    {
        foreach ($fields as $index => $field) {
            if (isset($field['name']) && $field['name'] === $fieldName) {
                unset($fields[$index]);
            } elseif (isset($field['sub_fields']) && is_array($field['sub_fields'])) {
                $fields[$index]['sub_fields'] = $this->recursiveRemove($field['sub_fields'], $fieldName);
            }
        }

        // Reindex the array 
        return array_values($fields);
    }


    /**
     * set conditional logic for current field 
     * operator = '==', '!=empty', !=,'==pattern', '==empty', '=contains',
     * @param mixed $field
     * @param mixed $operator
     * @param mixed $value
     * @return self
     */
    public function setConditionalLogic($field, $operator, $value)
    {
        if (!empty($this->fields)) {
            $fieldParam = (!empty($field) && is_array($field)) ? $field : $this->exactCurrentField;
            $this->updateField($fieldParam, [
                'conditional_logic' => [
                    [
                        [
                            'fieldPath' => $field,
                            'operator' => $operator,
                            'value' => $value
                        ],
                    ],

                ]
            ]);
        }
        return $this;
    }

    /**
     * Summary of setConditionalLogic
     * @param mixed $width
     * @param mixed $field
     * @return self
     */
    public function setWidth($width, $field = null)
    {
        if (!empty($this->fields)) {
            $fieldParam = (!empty($field) && is_array($field)) ? $field : $this->exactCurrentField;
            $this->updateField($fieldParam, [
                'wrapper' => [
                    'class' => '',
                    'id' => '',
                    'width' => $width
                ]
            ]);
        }
        return $this;
    }


    /**
     * Summary of updateField
     * @param mixed $field
     * @param mixed $updates
     * @return self
     */
    private function updateField($field, $updates)
    {
        $fields = &$this->fields;
        if (!empty($fields) && !empty($field)) {
            foreach ($fields as  &$value) {
                if (!empty($value) && is_array($value) && array_key_exists('name', $value) && $value['name'] === $field['name']) {
                    foreach ($updates as $updateKey => $updateValue) {
                        $value[$updateKey] = $updateValue;
                    }
                } else if (isset($value['sub_fields'])) {
                    $this->updateSubFields($field, $value, $updates);
                }
            }
        }
        return $this;
    }

  /**
     * Summary of updateSubFields
     * @param mixed $field
     * @param mixed $value
     * @param mixed $updates
     * @return self
     */
    private function updateSubFields($field, &$value, $updates)
    {
        if (empty($value) && empty($value['sub_fields'])) {
            return  $this;
        } else if (!empty($value['sub_fields'])) {
            foreach ($value['sub_fields'] as  &$subValue) {
                if (!empty($subValue) && $subValue['name'] == $field['name']) {
                    foreach ($updates as $key => $updateValue) {
                        $subValue[$key] = $updateValue;
                    }
                } else if (isset($subValue['sub_fields'])) {
                    $this->updateSubFields($field, $subValue['sub_fields'], $updates);
                }
            }
        }

        return $this;
    }

    /**
     * clone a field by name passing nothing by default will clone exact current field
     * @param string $name
     * @param array $overrides
     * @return \theme\AcfCreate
     */
    public function clone(string $name = '', array $overrides = []): self
    {
        $fields = !empty($name) ? $this->getField($name) : $this->exactCurrentField;
        $this->createField(
            $fields['name'] ?? '',
            $fields['label'] ?? '',
            $fields['type'] ?? '',
            $overrides
        );

        return $this;
    }
}
