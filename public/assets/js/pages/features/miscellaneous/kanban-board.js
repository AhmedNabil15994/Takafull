"use strict";

// Class definition

var KTKanbanBoardDemo = function() {
    // Private functions
    var _demo1 = function() {
        var kanban = new jKanban({
            element: '#kt_kanban_1',
            gutter: '0',
            boards: [{
                    'id': '_inprocess',
                    'title': '',
                    'item': [{
                            'title': '<span class="font-weight-bold">You can drag me too</span>'
                        },
                        {
                            'title': '<span class="font-weight-bold">Buy Milk</span>'
                        }
                    ]
                },
            ]
        });
    }

    // Public functions
    return {
        init: function() {
            _demo1();
        }
    };
}();

jQuery(document).ready(function() {
    KTKanbanBoardDemo.init();
});
