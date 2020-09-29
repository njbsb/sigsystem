$(document).ready(function() {
    $('#matric').change(function() {
        var matric = $('#matric').val();
        if (matric != '') {
            $.ajax({
                url: "<?php echo base_url(); ?>student/check_matric",
                method: "POST",
                data: {
                    matric: matric
                },
                success: function(data) {
                    $('#matric_result').html(data);
                }
            });
        }
    });
});