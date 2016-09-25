var handleJstreeAjax = function() {
	
    $('#arbol-publicacion').jstree({
        "core": {
            "themes": { "responsive": false },
            "check_callback": true,
            'data': {
                'url': function (node) {
                    return node.id === '#' ? 'publicacion/data_root.json': 'publicacion/' + node.original.file;
                },
                'data': function (node) {
                    return { 'id': node.id };
                },
                "dataType": "json"
            }
        },
        "types": {
            "default": { "icon": "fa fa-folder text-warning fa-lg" },
            "file": { "icon": "fa fa-file text-warning fa-lg" }
        },
        "plugins": [ "contextmenu", "dnd", "state", "types" ]
    });
  
};


var TreeView = function () {
	"use strict";
    return {
        //main function
        init: function () {
            handleJstreeAjax();
        }
    };
}();