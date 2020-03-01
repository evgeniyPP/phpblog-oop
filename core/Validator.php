<?php

namespace core;

class Validator
{
    const STRING = 'string';
    const INTEGER = 'integer';
    const DATE = 'date';
    const DATE_REGEX =
        '/^((?:19|20)\d{2})\-(1[012]|0[1-9])\-(3[01]|[12][0-9]|0[1-9]) ([0-1][0-9]|[2][0-3]):([0-5][0-9]):([0-5][0-9])$/';

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

            if ($this->isNotExistsButRequired($value, $rules)) {
                $this->errors[$name][] = 'Is Required';
                continue;
            }

            if ($this->isNotExists($value)) {
                continue;
            }

            if ($this->isNull($value)) {
                if ($this->cannotBeNull($value, $rules)) {
                    $this->errors[$name][] = 'Cannot Be Null';
                }
                continue;
            }

            if ($this->hasDefinedType($rules)) {
                if ($rules['type'] === self::STRING) {
                    if ($this->isTooShort($value, $rules)) {
                        $this->errors[$name][] = "Too Short. Minimum Length is {$rules['minLength']}";
                    } elseif ($this->isTooLong($value, $rules)) {
                        $this->errors[$name][] = "Too Long. Maximum Length is {$rules['maxLength']}";
                    }
                } elseif ($rules['type'] === self::INTEGER) {
                    if (!is_numeric($value)) {
                        $this->errors[$name][] = "Must Be Numeric";
                    }
                } elseif ($rules['type'] === self::DATE) {
                    if (!preg_match(self::DATE_REGEX, $value)) {
                        $this->errors[$name][] = "Incorrect Date";
                    }
                } else {
                    $this->errors[$name][] = "Unknown Type";
                }
            }

            if (empty($this->errors[$name])) {
                if ($rules['type'] === self::STRING) {
                    $this->clean[$name] = trim(htmlspecialchars($value));
                } elseif ($rules['type'] === self::INTEGER) {
                    $this->clean[$name] = (int) $value;
                } else {
                    $this->clean[$name] = $value;
                }
            }
        }

        if (count($this->clean) === count($fields)) {
            $this->success = true;
        }

        return true;
    }

    private function isNotExistsButRequired($value, $rules)
    {
        if (!isset($value) && isset($rules['required']) && $rules['required']) {
            return true;
        }
        return false;
    }

    private function isNotExists($value)
    {
        if (!isset($value)) {
            return true;
        }
        return false;
    }

    private function isNull($value)
    {
        if (trim($value) == null) {
            return true;
        }
        return false;
    }

    private function cannotBeNull($value, $rules)
    {
        if (!isset($rules['nullable']) || !$rules['nullable']) {
            return true;
        }
        return false;
    }

    private function hasDefinedType($rules)
    {
        if (isset($rules['type'])) {
            return true;
        }
        return false;
    }

    private function isTooShort($value, $rules)
    {
        if (isset($rules['minLength']) && mb_strlen($value) < $rules['minLength']) {
            return true;
        }
        return false;
    }

    private function isTooLong($value, $rules)
    {
        if (isset($rules['maxLength']) && mb_strlen($value) > $rules['maxLength']) {
            return true;
        }
        return false;
    }
}