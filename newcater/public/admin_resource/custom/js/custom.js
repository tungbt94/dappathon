$(document).ready(function ($) {

    $(document).on('click', '.delete-item', function () {
        return confirm('Bạn chắc chắn muốn xóa?');
    });

});

var Number_Lib = {
    addCommas: function (nStr) {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    },
    removeCommas: function (nStr) {
        if (typeof (nStr) === 'number') return nStr;
        if (!nStr || !nStr.length) return 0;
        return parseFloat(nStr.replace(/,/g, '')) || 0;
    }
};


function loadTinyMce(cl) {
    $('.' + cl).tinymce({
        script_url: '/admin_resource/custom/plugin/tinymce/tinymce.min.js',
        width: "100%",
        height: 200,
        theme_advanced_fonts: "Arial=arial;",
        //theme_advanced_font_sizes: "12px, 14px, 18px, 24px, 36px",
        fontsize_formats: "8pt 9pt 10pt 11pt 12pt 26pt 36pt",
        toolbar: "insertfile undo redo | styleselect | fontselect | fontsizeselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages | forecolor backcolor,media | table | fullscreen code",

        theme: "modern",
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars",
            "insertdatetime media nonbreaking save contextmenu directionality",
            "emoticons template paste textcolor colorpicker textpattern imagetools responsivefilemanager fullscreen",
            "code media table"
        ],
        extended_valid_elements: "audio[id|class|src|type|controls]",
        //extended_valid_elements: "audio[id|class|src|type|controls],script[language|type|src],style[type]",
        audio_template_callback: function (data) {
            return '<audio controls>' + '\n<source src="' + data.source1 + '"' + (data.source1mime ? ' type="' + data.source1mime + '"' : '') + ' />\n' + '</audio>';
        },
        menubar: "insert, table",
        media_live_embeds: true,
        media_alt_source: false,
        external_filemanager_path: "/admin_resource/custom/plugin/filemanager/",
        relative_urls: false,
        remove_script_host: false,
        filemanager_title: "Responsive Filemanager",
        external_plugins: {"filemanager": "/admin_resource/custom/plugin/filemanager/plugin.min.js"}
    });
}

function getTimestamp(dateTime) {
    let dateTimeParts = dateTime.split(' '),
        timeParts = dateTimeParts[1].split(':'),
        dateParts = dateTimeParts[0].split('/'),
        Timstamp;
    Timstamp = new Date(dateParts[2], parseInt(dateParts[0], 10) - 1, dateParts[1], timeParts[0], timeParts[1]);
    return Timstamp.getTime();
}