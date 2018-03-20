<script>
    Dropzone.autoDiscover = false;
    //configuration
    Dropzone.options.organizationPhoto = {
        maxFilesize: 1,
        uploadMultiple: false,
        addRemoveLinks: true,
        maxFiles: 1,
        init: function () {
            this.on("addedfile", function (file) {
            });
        },
        accept: function (file, done) {
            if (file.type !== "image/jpeg" && file.type !== 'image/png') {
                done("Error! Files of this type are not accepted");
            }
            else {
                done();
            }
        },
        success: function (file, response) {
            var filename = Dropzone.createElement("<input type='hidden' name='organization-image' id='organization-name' value='" + response.errors.file_name + "'>");
            file.previewElement.appendChild(filename);
        }
    };
    var myDropzone1 = new Dropzone("div#organization-photo", {url: "<?php echo $upload_url; ?>"});
    $(".dz-message").html('<i class="fa fa-cloud"></i><br><span class="h6">Drag & Drop to Upload File</span>');
</script>