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
			},
			responsive: true,
			searchDelay: 500,
			processing: true,
			serverSide: true,
			ajax: {
				url: '/backend/contactUs',
				type: 'GET',
				data:function(dtParms){
			       	dtParms.from = $('#kt_datetimepicker_7_1').val();
			        dtParms.to = $('#kt_datetimepicker_7_2').val();
			        dtParms.columnsDef= [
						'id', 'name','email','phone','message','reply','created_at'];
			        return dtParms
			    }
			},
			columns: [
				{data: 'id'},
				{data: 'name'},
				{data: 'email'},
				{data: 'phone'},
				{data: 'message'},
				{data: 'reply'},
				{data: 'created_at', type: 'date'},
				{data: 'id', responsivePriority: -1},
			],
			columnDefs: [
				{
					targets: 0,
					width: 75,
				},
				{
					targets: 1,
					title: 'الاسم بالكامل',
					className: 'edits',
					render: function(data, type, full, meta) {
						return '<a class="editable" data-col="name" data-id="'+full.id+'">'+data+'</a>';
					},
				},
				{
					targets: 2,
					title: 'البريد الالكتروني',
					className: 'edits',
					render: function(data, type, full, meta) {
						return '<a class="editable" data-col="email" data-id="'+full.id+'">'+data+'</a>';
					},
				},
				{
					targets: 3,
					title: 'الجوال',
					render: function(data, type, full, meta) {
						return '<a target="_BLANK" href="https://api.whatsapp.com/send?phone='+data+'">'+
									'<div style="width:100%;" class="btn btn-success btn-shadow-hover font-weight-bold mr-2" data-id="'+full.id+'">'+data+'</div>'+
								'</a>';
					},
				},
				{
					targets: 4,
					title: 'محتوي الرسالة',
					className: 'edits',
					render: function(data, type, full, meta) {
						return '<a class="editable" data-col="message" data-id="'+full.id+'">'+data+'</a>';
					},
				},
				{
					targets: 5,
					width: 100,
					title: 'الرد',
					className: 'edits',
					render: function(data, type, full, meta) {
						return '<a class="editable" data-col="reply" data-id="'+full.id+'">'+data+'</a>';
					},
				},
				{
					targets: 6,
					width: 150,
					title: 'تاريخ الارسال',
					className: 'edits dates',
					render: function(data, type, full, meta) {
						return '<a class="editable" data-col="created_at" data-id="'+full.id+'">'+data+'</a>';
					},
				},
				{
					targets: -1,
					title: 'الاجراءات',
					width: 100,
					orderable: false,
					render: function(data, type, full, meta) {
						var editButton = '';
						var replyButton = '';
						var deleteButton = '';
						if($('input[name="data-area"]').val() == 1){
							editButton = '<a href="/backend/contactUs/edit/'+data+'" class="dropdown-item">'+
		                                    '<i class="m-nav__link-icon fa fa-pencil-alt"></i>'+
		                                    '<span class="m-nav__link-text">تعديل</span>'+
		                                '</a>';

		                    replyButton = '<a href="/backend/contactUs/reply/'+data+'" class="dropdown-item">'+
		                                    '<i class="m-nav__link-icon fa fa-sync-alt"></i>'+
		                                    '<span class="m-nav__link-text">الرد علي الرسالة</span>'+
		                                '</a>';
						}

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
		                                editButton+
		                                replyButton+
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
