<?php

namespace AcfCreator;

class Base
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
