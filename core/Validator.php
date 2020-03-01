<?php

namespace core;

class Validator
{
    const STRING = 'string';
    const INTEGER = 'integer';
    const DATE = 'date';
    const DD_MM_YY_REGEX = '/^\s*(3[01]|[12][0-9]|0?[1-9])\.(1[012]|0?[1-9])\.((?:19|20)\d{2})\s*$/';

    public $clean = [];
    public $errors = [];
    public $success = false;
    private $rules;

    public function setRules(array $rules)
    {
        $this->rules = $rules;
    }

    public function execute(array $fields)
    {
        if (!$this->rules) {
            throw new \Exception('Правила не определены. Сначала вызовите setRules()');
        }

        foreach ($this->rules as $name => $rules) {
            $value = $fields[$name];

            if (!isset($value) && isset($rules['required']) && $rules['required']) {
                $this->errors[$name][] = 'Is Required';
                continue;
            }

            if (!isset($value)) {
                continue;
            }

            if (trim($value) == null) {
                if (!isset($rules['nullable']) || !$rules['nullable']) {
                    $this->errors[$name][] = 'Cannot Be Null';
                }
                continue;
            }

            if (isset($rules['type'])) {
                if ($rules['type'] === self::STRING) {
                    $fields[$name] = trim(htmlspecialchars($value));
                    if (isset($rules['minLength']) && strlen($value) < $rules['minLength']) {
                        $this->errors[$name][] = "Too Short. Minimum Length is {$rules['minLength']}";
                    } elseif (isset($rules['maxLength']) && strlen($value) > $rules['maxLength']) {
                        $this->errors[$name][] = "Too Long. Maximum Length is {$rules['maxLength']}";
                    }
                } elseif ($rules['type'] === self::INTEGER) {
                    if (!is_numeric($value)) {
                        $this->errors[$name][] = "Must Be Numeric";
                    }
                } elseif ($rules['type'] === self::DATE) {
                    if (!preg_match(self::DD_MM_YY_REGEX, $value)) {
                        $this->errors[$name][] = "Incorrect Date";
                    }
                } else {
                    $this->errors[$name][] = "Unknown Type";
                }
            }

            if (empty($this->errors[$name])) {
                $this->clean[$name] = $value;
            }
        }

        if (count($this->clean) === count($fields)) {
            $this->success = true;
        }

        return true;
    }
}