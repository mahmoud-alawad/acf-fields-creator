<?php

namespace AcfGenerator;

class Generator
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
    private array $nestedFieldStack = []; // Stack to handle nested repeaters

    public function __construct()
    {
        $this->fields = [];
        $this->currentField = [];
        $this->nestedFieldStack = [];
    }

    private function createField(string $name, string $label, string $type, array $overrides = []): array
    {

        $field = array_merge(
            self::$defaultFields,
            [
                'label' => $label,
                'name' => $name,
                'type' => $type,
            ],
            $overrides
        );
        $this->currentField = $field;
        if (!empty($this->nestedFieldStack)) {
            $currentRepeaterIndex = count($this->nestedFieldStack) - 1;
            $this->nestedFieldStack[$currentRepeaterIndex]['sub_fields'][] = $field;
        } else {
            $this->fields[] = $field;
        }
        return $field;
    }

    /**
     * returns field by it name example (new AcfCreate())->getField('title')
     * @param string $name
     * @return array
     */
    public function getField(string $name)
    {
        return array_filter($this->fields, function ($item) use ($name) {
            return !empty($item['name']) &&  $item['name'] === $name;
        });
    }

    /**
     * merge fields to current created fields example $acfCreateinstance->merge($fields)
     * @param array $fields
     * @return self
     */
    public  function merge(array $fields = [])
    {
        if (empty($fields)) {
            return $this;
        }
        $this->fields = array_merge($this->fields, $fields);
        return $this;
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
     * Summary of setConditionalLogic
     * @param mixed $field
     * @param mixed $operator
     * @param mixed $value
     * @return self
     */
    public function setConditionalLogic($field, $operator, $value)
    {
        if (!empty($this->fields)) {
            if (!key_exists('conditional_logic', $this->fields[count($this->fields) - 1])) {
                $this->fields[count($this->fields) - 1]['conditional_logic'] = [];
            }

            $this->fields[count($this->fields) - 1]['conditional_logic'][] = [
                [
                    'fieldPath' => $field,
                    'operator' => $operator,
                    'value' => $value
                ]
            ];
        }
        return $this;
    }


    /**
     * returns all fields
     * @return array
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    public function toJson(): string
    {
        return json_encode($this->fields, JSON_PRETTY_PRINT);
    }

    /**
     * 
     * @param string $name
     * @param string $label
     * @param array $overrides
     * @return \AcfGenerator\Generator
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
            $label ?: ucfirst($name),
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
     * @return \AcfGenerator\Generator
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
            $label ?: ucfirst($name),
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
     * @return \AcfGenerator\Generator
     */
    public function addOembed(string $name, string $label = '', array $overrides = []): self
    {
        $fieldDefaults = [
            'width' => '',
            'height' => '',
        ];

        $this->createField(
            $name,
            $label ?: ucfirst($name),
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
     * @return \AcfGenerator\Generator
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
            $label ?: ucfirst($name),
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
     * @return \AcfGenerator\Generator
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
            $label ?: ucfirst($name),
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
     * @return \AcfGenerator\Generator
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
            $label ?: ucfirst($name),
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
     * @return \AcfGenerator\Generator
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
            $label ?: ucfirst($name),
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
     * @return \AcfGenerator\Generator
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
            $label ?: ucfirst($name),
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
     * @return \AcfGenerator\Generator
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
            $label ?: ucfirst($name),
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
     * @return \AcfGenerator\Generator
     */
    public function addTime(string $name, string $label = '', array $overrides = []): self
    {
        $dateDefaults = [
            'display_format' => 'G:i',
            'return_format' => 'G:i',
        ];

        $this->createField(
            $name,
            $label ?: ucfirst($name),
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
     * @return \AcfGenerator\Generator
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
            $label ?: ucfirst($name),
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
     * @return \AcfGenerator\Generator
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
            $label ?: ucfirst($name),
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
     * @return \AcfGenerator\Generator
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
            $label ?: ucfirst($name),
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
     * @return \AcfGenerator\Generator
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
            $label ?: ucfirst($name),
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
     * @return \AcfGenerator\Generator
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
            $label ?: ucfirst($name),
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
     * @return \AcfGenerator\Generator
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
                'label' => $label ?: ucfirst($name),
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
     * @return \AcfGenerator\Generator
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
                'label' => $label ?: ucfirst($name),
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
     * @return \AcfGenerator\Generator
     */
    public function endGroup(): self
    {
        array_pop($this->nestedFieldStack);
        return $this;
    }

    /**
     * Summary of endRepeater
     * @return \AcfGenerator\Generator
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
     * @return \AcfGenerator\Generator
     */
    public function addLink(string $name, string $label = '',  array $overrides = []): self
    {
        $this->createField(
            $name,
            $label ?: ucfirst($name),
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
     * @return \AcfGenerator\Generator
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
            $label ?: ucfirst($name),
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
     * @return \AcfGenerator\Generator
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
            $label ?: ucfirst($name),
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
     * @return \AcfGenerator\Generator
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
            $label ?: ucfirst($name),
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
     * @return \AcfGenerator\Generator
     */
    public function addTab(string $name, string $label = '',  array $overrides = []): self
    {
        $defaultFields = [
            'placement' => 'top',
            'endpoint' => 0,
        ];
        $this->createField(
            $name,
            $label ?: ucfirst($name),
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
     * @return \AcfGenerator\Generator
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
            $label ?: ucfirst($name),
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
     * @return \AcfGenerator\Generator
     */
    public function addColor(string $name, string $label = '',  string $color = '#1e335e', array $overrides = []): self
    {
        $defaultFields = [
            'default_value' => $color,
        ];
        $this->createField(
            $name,
            $label ?: ucfirst($name),
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
     * @return \AcfGenerator\Generator
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
            $label ?: ucfirst($name),
            self::GOOGLEMAP,
            array_merge($defaultFields, $overrides)
        );

        return $this;
    }
}
