<?php
class WizardHelper
{
    public static function renderField($fieldName, $config, $value = '')
    {
        $required = $config['required'] ? 'required' : '';
        $html = '<div class="form-group">';
        $html .= '<label>' . $config['label'] . ($config['required'] ? ' <span class="required">*</span>' : '') . '</label>';

        switch ($config['type']) {
            case 'text':
            case 'number':
                $html .= '<input type="' . $config['type'] . '" name="' . $fieldName . '" class="form-control" placeholder="' . ($config['placeholder'] ?? '') . '" ' . $required . ' value="' . htmlspecialchars($value) . '">';
                break;

            case 'textarea':
                $html .= '<textarea name="' . $fieldName . '" class="form-control" rows="5" ' . $required . '>' . htmlspecialchars($value) . '</textarea>';
                break;

            case 'select':
                $html .= '<select name="' . $fieldName . '" class="form-control" ' . $required . '>';
                $html .= '<option value="">Select an option</option>';
                foreach ($config['options'] as $option) {
                    $selected = ($value == $option) ? 'selected' : '';
                    $html .= '<option value="' . $option . '" ' . $selected . '>' . $option . '</option>';
                }
                $html .= '</select>';
                break;

            case 'multiselect':
                $selectedValues = is_array($value) ? $value : [];
                $html .= '<select name="' . $fieldName . '[]" class="form-control" multiple ' . $required . '>';
                foreach ($config['options'] as $option) {
                    $selected = in_array($option, $selectedValues) ? 'selected' : '';
                    $html .= '<option value="' . $option . '" ' . $selected . '>' . $option . '</option>';
                }
                $html .= '</select>';
                $html .= '<small class="form-text text-muted">Hold Ctrl to select multiple</small>';
                break;
        }

        $html .= '</div>';
        return $html;
    }
}