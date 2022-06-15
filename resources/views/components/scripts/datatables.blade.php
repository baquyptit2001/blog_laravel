<script>
    $(document).ready(function () {
        $('#example').DataTable({
            "ordering": true,
            columnDefs: [
                {
                    orderable: false,
                    targets: "no-sort",
                },
                {
                    searchable: false,
                    targets: "no-search",
                }
            ]
        });
    });
</script>
