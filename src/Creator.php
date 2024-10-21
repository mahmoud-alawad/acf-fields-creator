<?php

namespace AcfCreator;

class Creator
{

    const TEXT = "text";
    const IMAGE = "image";
    const FILE = "file";
    const LINK = "link";
    const SELECT = "select";
    const EMAIL = "email";
    const GALLERY = "gallery";
    const REPEATER  = "repeater";
    const TEXTAREA = "textarea";
    const WYSIWYG = "wysiwyg";
    const TRUEFALSE = "true_false";
    const POSTOBJECT = "post_object";
    const PAGELINK = "page_link";
    const TAXONOMY = "taxonomy";
    const RELATIONSHIP = "relationship";
    const TAB = "tab";
    const MESSAGE  = "message";
    const COLOR = "color_picker";
    const GOOGLEMAP = "google_map";
    const DATE = "date_picker";
    const TIME = "time_picker";
    const NUMBER = "number";
    const GROUP = "group";
    const ACCORDION = "accordion";
    const OEMBED = "oembed";

    protected static $defaultFields = [
        'instructions' => '',
        'required' => 0,
        'wrapper' => [
            'width' => '',
            'class' => '',
            'id' => '',
        ],
    ];

    public array $fields = [];
    public array $currentField = [];
    public array $exactCurrentField = [];

    protected array $nestedFieldStack = []; // Stack to handle nested repeaters

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
     * @return self
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

    protected function createField(string $name, string $label, string $type, array $overrides = []): array
    {

        $field = array_merge(
            self::$defaultFields,
            [
                'label' => empty($label) ? $this->convertToTitleCase($name) : $label,
                'name' => $name,
                'type' => $type,
            ],
            $overrides
        );
        $this->currentField = $field;
        $this->exactCurrentField = $field;
        if (!empty($this->nestedFieldStack)) {
            $currentRepeaterIndex = count($this->nestedFieldStack) - 1;
            $this->nestedFieldStack[$currentRepeaterIndex]['sub_fields'][] = $field;
            $this->currentField =  $this->nestedFieldStack[$currentRepeaterIndex];
        } else {
            $this->fields[] = $field;
        }

        return $field;
    }

    protected function convertToTitleCase($input)
    {
        if (strlen($input) <= 2) {
            return ucfirst($input);
        }
        if (strpos($input, '_') !== false) {
            $input = str_replace('_', ' ', $input);
            $input = ucwords($input);
        } else {
            $input = preg_replace('/(?<!^)([A-Z])/', ' $1', $input);
            $input = ucwords($input);
        }

        return $input;
    }

    /**
     * 
     * @param string $name
     * @param string $label
     * @param array $overrides
     * @return self
     */
    public function addText(string $name, string $label = '', array $overrides = []): self
    {
        $textDefaults = [
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'maxlength' => '',
        ];

        $this->createField(
            $name,
            $label,
            self::TEXT,
            array_merge($textDefaults, $overrides)
        );

        return $this;
    }


    /**
     * 
     * @param string $name
     * @param string $label
     * @param array $overrides
     * @return self
     */
    public function addAccordion(string $name, string $label = '', array $overrides = []): self
    {
        $fieldDefaults = [
            'open' => 0,
            'multi_expand' => 0,
            'endpoint' => 0,
        ];

        $this->createField(
            $name,
            $label,
            self::ACCORDION,
            array_merge($fieldDefaults, $overrides)
        );

        return $this;
    }

    /**
     * 
     * @param string $name
     * @param string $label
     * @param array $overrides
     * @return self
     */
    public function addOembed(string $name, string $label = '', array $overrides = []): self
    {
        $fieldDefaults = [
            'width' => '',
            'height' => '',
        ];

        $this->createField(
            $name,
            $label,
            self::OEMBED,
            array_merge($fieldDefaults, $overrides)
        );

        return $this;
    }


    /**
     * 
     * @param string $name
     * @param string $label
     * @param array $overrides
     * @return self
     */
    public function addTaxonomy(string $name, string $label = '', array $overrides = []): self
    {
        $taxonomyDefaults = [
            'taxonomy' => 'category',
            'field_type' => 'checkbox',
            'add_term' => 1,
            'save_terms' => 0,
            'load_terms' => 0,
            'return_format' => 'object',
            'multiple' => 0,
            'allow_null' => 0,
        ];

        $this->createField(
            $name,
            $label,
            self::TAXONOMY,
            array_merge($taxonomyDefaults, $overrides)
        );

        return $this;
    }

    /**
     * 
     * @param string $name
     * @param string $label
     * @param array $overrides
     * @return self
     */
    public function addRelationShip(string $name, string $label = '', array $overrides = []): self
    {
        $relationDefaults = [
            'post_type' =>
            [
                'page',
                'post',
            ],
            'taxonomy' => [],
            'filters' =>
            [
                'search',
                'post_type',
                'taxonomy',
            ],
            'elements' => ['featured_image'],
            'min' => '',
            'max' => '',
            'return_format' => 'object',
        ];

        $this->createField(
            $name,
            $label,
            self::RELATIONSHIP,
            array_merge($relationDefaults, $overrides)
        );

        return $this;
    }

    /**
     * 
     * @param string $name
     * @param string $label
     * @param array $overrides
     * @return self
     */
    public function addPostObject(string $name, string $label = '', array $overrides = []): self
    {
        $fieldDefaults = [
            'post_type' =>
            [
                'page',
                'post',
            ],
            'taxonomy' => [],
            'filters' =>
            [
                'search',
                'post_type',
                'taxonomy',
            ],
            'elements' => ['featured_image'],
            'min' => '',
            'max' => '',
            'allow_null' => 0,
            'multiple' => 0,
            'return_format' => 'object',
            'ui' => 1,
        ];

        $this->createField(
            $name,
            $label,
            self::POSTOBJECT,
            array_merge($fieldDefaults, $overrides)
        );

        return $this;
    }

    /**
     * 
     * @param string $name
     * @param string $label
     * @param array $overrides
     * @return self
     */
    public function addPageLink(string $name, string $label = '', array $overrides = []): self
    {
        $fieldDefaults = [
            'post_type' =>
            [
                'page',
                'post',
            ],
            'taxonomy' => [],
            'filters' =>
            [
                'search',
                'post_type',
                'taxonomy',
            ],
            'allow_null' => 0,
            'allow_archives' => 1,
            'multiple' => 0,
        ];

        $this->createField(
            $name,
            $label,
            self::PAGELINK,
            array_merge($fieldDefaults, $overrides)
        );

        return $this;
    }


    /**
     * 
     * @param string $name
     * @param string $label
     * @param array $overrides
     * @return self
     */
    public function addNumber(string $name, string $label = '', array $overrides = []): self
    {
        $imageDefaults = [
            'default_value' => '',
            'min' => '',
            'max' => '',
            'step' => '',
            'prepend' => '',
            'append' => '',
        ];

        $this->createField(
            $name,
            $label,
            self::NUMBER,
            array_merge($imageDefaults, $overrides)
        );

        return $this;
    }

    /**
     * 
     * @param string $name
     * @param string $label
     * @param array $overrides
     * @return self
     */
    public function addDate(string $name, string $label = '', array $overrides = []): self
    {
        $dateDefaults = [
            'display_format' => 'd/m/Y',
            'return_format' => 'd/m/Y',
            'first_day' => 1,
        ];

        $this->createField(
            $name,
            $label,
            self::DATE,
            array_merge($dateDefaults, $overrides)
        );

        return $this;
    }

    /**
     * 
     * @param string $name
     * @param string $label
     * @param array $overrides
     * @return self
     */
    public function addTime(string $name, string $label = '', array $overrides = []): self
    {
        $dateDefaults = [
            'display_format' => 'G:i',
            'return_format' => 'G:i',
        ];

        $this->createField(
            $name,
            $label,
            self::TIME,
            array_merge($dateDefaults, $overrides)
        );

        return $this;
    }

    /**
     * 
     * @param string $name
     * @param string $label
     * @param array $overrides
     * @return self
     */
    public function addImage(string $name, string $label = '', array $overrides = []): self
    {
        $imageDefaults = [
            'return_format' => 'array',
            'preview_size' => 'medium',
            'library' => 'all',
            'min_width' => '',
            'min_height' => '',
            'min_size' => '',
            'max_width' => '',
            'max_height' => '',
            'max_size' => '',
            'mime_types' => '',
        ];

        $this->createField(
            $name,
            $label,
            self::IMAGE,
            array_merge($imageDefaults, $overrides)
        );

        return $this;
    }


    /**
     * 
     * @param string $name
     * @param string $label
     * @param array $overrides
     * @return self
     */
    public function addFile(string $name, string $label = '', array $overrides = []): self
    {
        $defaultFields = [
            'return_format' => 'array',
            'library' => 'all',
            'min_size' => '',
            'max_size' => '',
            'mime_types' => '',
        ];

        $this->createField(
            $name,
            $label,
            self::FILE,
            array_merge($defaultFields, $overrides)
        );

        return $this;
    }


    /**
     * 
     * @param string $name
     * @param string $label
     * @param array $choices
     * @param array $overrides
     * @return self
     */
    public function addSelect(string $name, string $label = '', array $choices = [], array $overrides = []): self
    {
        $defaultFields = [
            'choices' =>
            $choices ?: [],
            'default_value' =>
            [],
            'allow_null' => 1,
            'multiple' => 0,
            'ui' => 0,
            'return_format' => 'value',
            'ajax' => 0,
            'placeholder' => '',
        ];

        $this->createField(
            $name,
            $label,
            self::SELECT,
            array_merge($defaultFields, $overrides)
        );

        return $this;
    }

    /**
     * 
     * @param string $name
     * @param string $label
     * @param array $overrides
     * @return self
     */
    public function addEmail(string $name, string $label = '',  array $overrides = []): self
    {
        $defaultFields = [
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
        ];

        $this->createField(
            $name,
            $label,
            self::EMAIL,
            array_merge($defaultFields, $overrides)
        );

        return $this;
    }

    /**
     * 
     * @param string $name
     * @param string $label
     * @param array $overrides
     * @return self
     */
    public function addTrueFalse(string $name, string $label = '',  array $overrides = []): self
    {
        $defaultFields = [
            'message' => '',
            'default_value' => 0,
            'ui' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
        ];

        $this->createField(
            $name,
            $label,
            self::TRUEFALSE,
            array_merge($defaultFields, $overrides)
        );

        return $this;
    }




    /**
     * 
     * @param string $name
     * @param string $label
     * @param array $overrides
     * @return self
     */
    public function addRepeater(string $name, string $label = '',   array $overrides = []): self
    {

        $defaultFields = [
            'collapsed' => '',
            'min' => 0,
            'max' => 0,
            'layout' => 'row',
            'button_label' => '+ Add',
            'sub_fields' => [],
        ];

        $repeaterField = array_merge(
            self::$defaultFields,
            [
                'label' => empty($label) ? $this->convertToTitleCase($name) : $label,
                'name' => $name,
                'type' => self::REPEATER,
            ],
            $defaultFields,
            $overrides
        );

        if (!empty($this->nestedFieldStack)) {
            $lastRepeaterIndex = count($this->nestedFieldStack) - 1;
            $this->nestedFieldStack[$lastRepeaterIndex]['sub_fields'][] = &$repeaterField;
        } else {
            $this->fields[] = &$repeaterField;
        }

        $this->nestedFieldStack[] = &$repeaterField;
        return $this;
    }


    /**
     * 
     * @param string $name
     * @param string $label
     * @param array $overrides
     * @return self
     */
    public function addGroup(string $name, string $label = '',   array $overrides = []): self
    {

        $defaultFields = [
            'layout' => 'block',
            'sub_fields' => [],
        ];


        $groupField = array_merge(
            self::$defaultFields,
            [
                'label' => empty($label) ? $this->convertToTitleCase($name) : $label,
                'name' => $name,
                'type' => self::GROUP,
            ],
            $defaultFields,
            $overrides
        );

        if (!empty($this->nestedFieldStack)) {
            $currentContainerIndex = count($this->nestedFieldStack) - 1;
            $this->nestedFieldStack[$currentContainerIndex]['sub_fields'][] = &$groupField;
        } else {
            $this->fields[] = &$groupField;
        }
        $this->nestedFieldStack[] = &$groupField;
        return $this;
    }



    /**
     * Summary of endGroup
     * @return self
     */
    public function endGroup(): self
    {
        array_pop($this->nestedFieldStack);
        return $this;
    }

    /**
     * Summary of endRepeater
     * @return self
     */
    public function endRepeater(): self
    {
        array_pop($this->nestedFieldStack);
        return $this;
    }

    /**
     * 
     * @param string $name
     * @param string $label
     * @param array $overrides
     * @return self
     */
    public function addLink(string $name, string $label = '',  array $overrides = []): self
    {
        $this->createField(
            $name,
            $label,
            self::LINK,
            array_merge(['return_format' => 'array'], $overrides)
        );

        return $this;
    }

    /**
     * 
     * @param string $name
     * @param string $label
     * @param array $overrides
     * @return self
     */
    public function addGallery(string $name, string $label = '',  array $overrides = []): self
    {
        $defaultFields = [
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'return_format' => 'array',
            'preview_size' => 'medium',
            'insert' => 'append',
            'library' => 'all',
            'min' => '',
            'max' => '',
            'min_width' => '',
            'min_height' => '',
            'min_size' => '',
            'max_width' => '',
            'max_height' => '',
            'max_size' => '',
            'mime_types' => '',
        ];
        $this->createField(
            $name,
            $label,
            self::GALLERY,
            array_merge($defaultFields, $overrides)
        );

        return $this;
    }

    /**
     * 
     * @param string $name
     * @param string $label
     * @param array $overrides
     * @return self
     */
    public function addTextarea(string $name, string $label = '', array $overrides = []): self
    {
        $defaultFields = [
            'default_value' => '',
            'placeholder' => '',
            'maxlength' => '',
            'rows' => 3,
            'new_lines' => 'br',
        ];
        $this->createField(
            $name,
            $label,
            self::TEXTAREA,
            array_merge($defaultFields, $overrides)
        );

        return $this;
    }


    /**
     * 
     * @param string $name
     * @param string $label
     * @param array $overrides
     * @return self
     */
    public function addWysiwg(string $name, string $label = '', array $overrides = []): self
    {
        $defaultFields = [
            'default_value' => '',
            'tabs' => 'all',
            'toolbar' => 'full',
            'media_upload' => 1,
            'delay' => 0,
        ];
        $this->createField(
            $name,
            $label,
            self::WYSIWYG,
            array_merge($defaultFields, $overrides)
        );

        return $this;
    }


    /**
     * 
     * @param string $name
     * @param string $label
     * @param array $overrides
     * @return self
     */
    public function addTab(string $name, string $label = '',  array $overrides = []): self
    {
        $defaultFields = [
            'placement' => 'top',
            'endpoint' => 0,
        ];
        $this->createField(
            $name,
            $label,
            self::TAB,
            array_merge($defaultFields, $overrides)
        );

        return $this;
    }

    /**
     * 
     * @param string $name
     * @param string $label
     * @param array $overrides
     * @return self
     */
    public function addMessage(string $name, string $label = '',  array $overrides = []): self
    {
        $defaultFields = [
            'message' => 'Nessuna impostazione disponibile per questo blocco.',
            'new_lines' => 'wpautop',
            'esc_html' => 0,
        ];
        $this->createField(
            $name,
            $label,
            self::MESSAGE,
            array_merge($defaultFields, $overrides)
        );

        return $this;
    }


    /**
     * 
     * @param string $name
     * @param string $label
     * @param array $color
     * @param array $overrides
     * @return self
     */
    public function addColor(string $name, string $label = '',  string $color = '#1e335e', array $overrides = []): self
    {
        $defaultFields = [
            'default_value' => $color,
        ];
        $this->createField(
            $name,
            $label,
            self::COLOR,
            array_merge($defaultFields, $overrides)
        );

        return $this;
    }

    /**
     * passing $coords like ['lat'=> '123','lng'=>'123']
     * @param string $name
     * @param string $label
     * @param array $coords
     * @param array $overrides
     * @return self
     */
    public function addGoogleMap(string $name, string $label = '',  array $coords = ['lat' => '', 'lng' => ''], array $overrides = []): self
    {
        $defaultFields = [
            'center_lat' =>  $coords['lat'],
            'center_lng' => $coords['lng'],
            'zoom' => '',
            'height' => '',
        ];
        $this->createField(
            $name,
            $label,
            self::GOOGLEMAP,
            array_merge($defaultFields, $overrides)
        );

        return $this;
    }
}
