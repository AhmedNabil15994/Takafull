"use strict";
var KTDatatablesAdvancedMultipleControls = function() {
	var init = function() {
		var table = $('#kt_datatable');

		// begin first table
		var DataTable = table.DataTable({
			// DOM Layout settings
			dom:'Bfrtip',
			dom:
				"<'row'<'col-xs-12 col-sm-6 col-md-6'l><'col-xs-12 col-sm-6 col-md-6 text-right'Bf>>" +
				"<'row'<'col-xs-6 col-sm-6 col-md-6'i><'col-xs-6 col-sm-6 col-md-6'p>> " +
				"<'row'<'col-sm-12 'tr>>" +
				"<'row'<'col-xs-4 col-sm-6 col-md-6 'l><'col-xs-8 col-sm-6 col-md-6  text-right'f>>" +
				"<'row'<'col-xs-6 col-sm-6 col-md-6 'i><'col-xs-6 col-sm-6 col-md-6 'p>>", // read more: https://datatables.net/examples/basic_init/dom.html
	        buttons: [
	            {
	                extend: 'colvis',
	                columns: ':not(.noVis)',
	                text: "<i class='fa fas fa-angle-down'></i> عرض الأعمدة",
	            },
	            {
	             	extend: 'print',
	             	customize: function (win) {
                       $(win.document.body).css('direction', 'rtl');     
                    },
 					'exportOptions': {
				    	columns: ':not(:last-child)',
				  	},
	         	},
	         	{
	             	extend: 'copy',
 					'exportOptions': {
				    	columns: ':not(:last-child)',
				  	},
	         	},
	         	{
	             	extend: 'excel',
 					'exportOptions': {
				    	columns: ':not(:last-child)',
				  	},
	         	},
	         	{
	             	extend: 'csv',
 					'exportOptions': {
				    	columns: ':not(:last-child)',
				  	},
	         	},
	         	{
	             	extend: 'pdf',
 					'exportOptions': {
				    	columns: ':not(:last-child)',
				  	},
	         	},
	        ],
	        oLanguage: {
				sSearch: "  البحث: ",
				sInfo: 'يتم العرض من  _START_ الي _END_ (العدد الكلي للسجلات _TOTAL_ )',
				sLengthMenu: 'عرض _MENU_ سجلات',
				sEmptyTable: "لا يوجد نتائج مسجلة",
				sProcessing: "جاري التحميل",
				sInfoEmpty: "لا يوجد نتائج مسجلة",
				select:{
					rows: {
                    	_: "لقد قمت باختيار %d عناصر",
	                    0: "",
	                    1: "لقد قمت باختيار عنصر واحد"
	                }
				}
			},
			responsive: true,
			searchDelay: 500,
			processing: true,
			serverSide: true,
			stateSave: true,
			select: {
				style: 'multi',
				selector: 'td:nth-child(2) .checkable',
			},
			ajax: {
				url: '/backend/'+$('input.url').val(),
				type: 'GET',
				data:function(dtParms){
			       	dtParms.created_at = $('#kt_datetimepicker_7_1').val();
			       	dtParms.status = $('select[name="status"]').val();
			        dtParms.columnsDef= [
						'id', 'name','identity','phone','city','address','statusText','created_at','notes'];
			        return dtParms
			    }
			},
			columns: [
				{data: 'id'},
				{data: 'id'},
				{data: 'name'},
				{data: 'identity',},
				{data: 'phone',},
				{data: 'city',},
				{data: 'address',},
				{data: 'statusText',},
				{data: 'created_at', type: 'date'},
				{data: 'notes',},
				{data: 'id', responsivePriority: -1},
			],
			columnDefs: [
				{
					targets: 0,
					width: 75,
				},
				{
					targets: 1,
					orderable: false,
					render: function(data, type, full, meta) {
						return '<label class="checkbox checkbox-single checkbox-primary mb-0">'+
                            '<input type="checkbox" value="" data-cols="'+full.id+'" class="checkable"/>'+
                            '<span></span>'+
                        '</label>';
					},
				},
				{
					targets: 2,
					title: 'الاسم',
					className: 'edits',
					render: function(data, type, full, meta) {
						return '<a class="editable" data-col="name" data-id="'+full.id+'">'+data+'</a>';
					},
				},
				{
					targets: 3,
					title: 'رقم الهوية او جواز السفر',
					className: 'edits',
					render: function(data, type, full, meta) {
						return '<a class="editable" data-col="identity" data-id="'+full.id+'">'+data+'</a>';
					},
				},
				{
					targets: 4,
					title: 'رقم الجوال',
					className: 'edits',
					render: function(data, type, full, meta) {
						return '<a class="editable" data-col="phone" data-id="'+full.id+'">'+data+'</a>';
					},
				},
				{
					targets: 5,
					title: 'المدينة',
					className: 'edits',
					render: function(data, type, full, meta) {
						return '<a class="editable" data-col="city" data-id="'+full.id+'">'+data+'</a>';
					},
				},
				{
					targets: 6,
					title: 'العنوان',
					className: 'edits',
					render: function(data, type, full, meta) {
						return '<a class="editable" data-col="address" data-id="'+full.id+'">'+data+'</a>';
					},
				},
				{
					targets: 7,
					title: 'الحالة',
					className: 'edits selects',
					render: function(data, type, full, meta) {
						return '<a class="editable" data-col="status" data-id="'+full.id+'"><div class="btn btn-raised btn-warning waves-effect">'+data+'</div></a>';
					},
				},
				{
					targets: 8,
					title: 'تاريخ الارسال',
					className: 'edits dates',
					render: function(data, type, full, meta) {
						return '<a class="editable" data-col="created_at" data-id="'+full.id+'">'+data+'</a>';
					},
				},
				{
					targets: 9,
					title: 'ملاحظات ادارية',
					className: 'edits',
					render: function(data, type, full, meta) {
						return '<a class="editable" data-col="notes" data-id="'+full.id+'">'+data+'</a>';
					},
				},
				{
					targets: -1,
					title: 'الاجراءات',
					width: 100,
					orderable: false,
					render: function(data, type, full, meta) {
						var deleteButton = '';
						if($('input[name="data-cols"]').val() == 1){
							deleteButton = '<a href="#" class="dropdown-item" onclick="deleteItem('+data+')">'+
		                                    '<i class="m-nav__link-icon fa fa-trash"></i>'+
		                                    '<span class="m-nav__link-text">حذف</span>'+
		                                '</a>';
						}
						return '<div class="main-menu dropdown dropdown-inline">'+
		                            '<button type="button" class="btn btn-light-primary btn-icon btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'+
		                                '<i class="ki ki-bold-more-hor"></i>'+
		                            '</button>'+
		                            '<div class="dropdown-menu" dropdown-toggle="hover">'+
		                                deleteButton+
		                            '</div>'+
		                        '</div>';
					},
				},
			],
		});

		if ($("#m_search")[0]) {
		    $("#m_search").on("click", function (t) {
		        t.preventDefault();
		        var e = {};
		        $(".m-input").each(function () {
		            var a = $(this).data("col-index");
		            e[a] ? e[a] += "|" + $(this).val() : e[a] = $(this).val();
		        }), $.each(e, function (t, e) {
		            DataTable.column(t).search(e || "", !1, !1);
		        }), DataTable.table().draw();
		    });
		}

		$('.selectAll').on('click',function(e){
		    e.preventDefault();
		    e.stopPropagation();
		    DataTable.rows().select();
    		$('table input[type="checkbox"]').attr('checked','checked');
		    $('table input[type="checkbox"]').parents('tr').addClass('selected');
		});

		$('.deselectAll').on('click',function(e){
		    e.preventDefault();
		    e.stopPropagation();
		    DataTable.rows().deselect();
    		$('table input[type="checkbox"]').attr('checked', false);
		    $('input[type="checkbox"]').parents('tr').removeClass('selected');
		});
	};

	return {
		//main function to initiate the module
		init: function() {
			init();
		}
	};

}();

jQuery(document).ready(function() {
	KTDatatablesAdvancedMultipleControls.init();

	$('.print-but').on('click',function(e){
	    e.preventDefault();
	    e.stopPropagation();
	    $('.buttons-print')[0].click();
	});

	$('.copy-but').on('click',function(e){
	    e.preventDefault();
	    e.stopPropagation();
	    $('.buttons-copy')[0].click();
	});

	$('.excel-but').on('click',function(e){
	    e.preventDefault();
	    e.stopPropagation();
	    $('.buttons-excel')[0].click();
	});

	$('.csv-but').on('click',function(e){
	    e.preventDefault();
	    e.stopPropagation();
	    $('.buttons-csv')[0].click();
	});

	$('.pdf-but').on('click',function(e){
	    e.preventDefault();
	    e.stopPropagation();
	    $('.buttons-pdf')[0].click();
	});


});
