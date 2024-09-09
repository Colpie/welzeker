jQuery(document).ready(function ($) {
    var partner = 0;
    var category = 'all';
    var freesearch = 'all';

    $('#docusearch_form #partner').change(function (e) {
        partner = $(this).val();
        $('#documentcenter #freesearch_form').hide();
        $('#documentcenter #results').hide();
        $('#free-search').val('');
        doCount();
    });

    $('#docusearch_form #category').change(function (e) {
        category = $(this).val();
        $('#documentcenter #freesearch_form').hide();
        $('#documentcenter #results').hide();
        $('#free-search').val('');
        doCount();
    });

    $('#docusearch_form').submit(function (e) {
        e.preventDefault();

        var partner = $('#documentcenter #partner').val();
        var category = $('#documentcenter #category').val();

        if (partner != 0) {
            $('#documentcenter #loader').show();

            freesearch = $('#documentcenter #free-search').val();
            if (freesearch == '') {
                freesearch = 'all';
            }

            $.ajax({
                url: "/wp-admin/admin-ajax.php",
                type: 'post',
                data: {
                    action: 'docusearch',
                    partner: partner,
                    category: category,
                    freesearch: freesearch,
                },
                success: function (data) {
                    $('#documentcenter #loader').hide();
                    $('#documentcenter #freesearch_form').show();
                    $('#documentcenter #results').show();
                    $('#documentcenter #results').html(data);
                }
            });
        }
        else {
            alert(script_texts.select_partner);
        }
        doCount();
    });

    function doCount() {
        $('#documentcenter #loader').show();

        $.ajax({
            url: "/wp-admin/admin-ajax.php",
            type: 'post',
            data: {
                action: 'docucount',
                partner: partner,
                category: category,
                freesearch: freesearch,
            },
            success: function (data) {
                $('#documentcenter #nrresults').html(data);
                $('#documentcenter #loader').hide();
            }
        });
    }
});
