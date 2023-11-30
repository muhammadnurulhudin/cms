
    <script type="text/javascript">
          window.addEventListener('DOMContentLoaded', function() {
            // $('#chk').prop('checked', false);
            var sort_col = $('.datat').find("th:contains('Dibuat')")[0].cellIndex;
          var table = $('.datat').DataTable({
              processing: true,
              serverSide: true,
              aaSorting: [],

              ajax: {
                "url" : "{{admin_url(get_post_type().'/dataindex')}}",
            },
            lengthMenu: [10, 20, 50, 100, 200, 500],
            deferRender: true,
            columns: [
                // {className: 'text-center',data: 'checkbox', name: 'checkbox',orderable:false,searchable: false},
                {className: 'text-center',data: 'DT_RowIndex', name: 'DT_RowIndex',orderable:false,searchable: false},
                @if(get_module_info('thumbnail')) {data: 'thumbnail',searchable: false, name: 'post_thumbnail',orderable: false}, @endif
                {data: 'post_title',searchable: true, name: 'post_title',orderable: false},
                @if(get_module_info('post_parent')) {data: 'parents', name: 'parents',orderable: false,searchable: true}, @endif
                @if(get_module_info('custom_column')) {data: 'data_field', name: 'data_field',orderable: false,searchable: true}, @endif
                {data: 'created_at', name: 'created_at',orderable: true,searchable: false},

                @if(get_post_type()!='media'){data: 'updated_at', name: 'updated_at',orderable: true,searchable: false},@endif
              @if(get_module_info('detail')){data: 'visited', name: 'visited',orderable: true,searchable: false},@endif
              {className: 'text-center',data: 'aksi', name: 'aksi',orderable: false,searchable: false},
            ],
            responsive:true,
            order: [[sort_col, 'asc']],
          });


          });

    </script>
