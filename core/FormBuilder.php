<?php

namespace core;

class FormBuilder
{
    public $form;

    public function __construct(Form &$form)
    {
        $this->form = $form;
    }

    public function method()
    {
        return $this->form->getMethod() ?? \METHOD_GET;
    }

    public function fields()
    {
        $inputs = [];

        foreach ($this->form->getFields() as $field) {
            if (isset($field['label'])) {
                $label = $field['label'];
                $attributes = array_filter($field, function ($attr) {
                    return $attr !== 'label';
                });
                $input = $this->input($attributes);
                $inputs[] = $this->label($label, $input);
            } else {
                $inputs[] = $this->input($field);
            }
        }

        return $inputs;
    }

    private function input($attributes)
    {
        return sprintf('<input %s>', $this->attributes($attributes));
    }

    private function label($label, $input)
    {
        $classes = isset($label['class']) ? sprintf('class=%s', $label['class']) : '';

        if (isset($label['positionAfter']) && $label['positionAfter']) {
            return sprintf('<label %s>%s%s</label>', $classes, $input, $label['value']);
        }

        return sprintf('<label %s>%s%s</label>', $classes, $label['value'], $input);
    }

    private function attributes($attributes)
    {
        $array = [];

        foreach ($attributes as $attribute => $value) {
            $array[] = sprintf('%s="%s"', $attribute, $value);
        }

        return implode(' ', $array);
    }
}