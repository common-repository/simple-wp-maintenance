jQuery(document).ready(function($) {
    $('.wp-color-picker-field').wpColorPicker({
        defaultColor: false,
        change: function(event, ui) {},
        clear: function() {},
        hide: true,
        palettes: true,
        width: 250,
        mode: 'hsl',
        type: 'full',
        slider: 'horizontal',
        alpha: true
    });
});
