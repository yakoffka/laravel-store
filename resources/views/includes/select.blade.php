<div class="form-group">
    <label for="{{ $name }}">{{ $name }}</label>
    <select name="{{ $name }}" id="{{ $name }}">
        @php
        foreach($options as $option) {
            $selected = (empty($option['selected'])) ? '' : ' selected';
            echo '<option value="' . $option['value'] . '"' . $selected . '>' . $option['title'] . '</option>';
        }@endphp
    </select>
</div>
