$(document).ready(function() {

    function updateFormFields() {
        const productType = $('#productType').val();
        const specificAttributesContainer = $('#specificAttributes');
        const typeNotification = $('#typeNotification');
    
        specificAttributesContainer.empty();
    
        if (productType === 'DVD') {
            specificAttributesContainer.html(`
                <div class="form-group">
                    <label for="size">Size (MB)</label>
                    <input type="number" id="size" name="size" required min="0">
                    <div class="notification" id="sizeNotification"></div>
                </div>
            `);
        } else if (productType === 'Book') {
            specificAttributesContainer.html(`
                <div class="form-group">
                    <label for="weight">Weight (KG)</label>
                    <input type="number" id="weight" name="weight" required min="0">
                    <div class="notification" id="weightNotification"></div>
                </div>
            `);
        } else if (productType === 'Furniture') {
            specificAttributesContainer.html(`
                <div class="form-group">
                    <label for="height">Height (CM)</label>
                    <input type="number" id="height" name="height" required min="0">
                    <div class="notification" id="heightNotification"></div>
                </div>
                <div class="form-group">
                    <label for="width">Width (CM)</label>
                    <input type="number" id="width" name="width" required min="0">
                    <div class="notification" id="widthNotification"></div>
                </div>
                <div class="form-group">
                    <label for="length">Length (CM)</label>
                    <input type="number" id="length" name="length" required min="0">
                    <div class="notification" id="lengthNotification"></div>
                </div>
            `);
        } else {
            specificAttributesContainer.html('');
            typeNotification.text('Please choose Type of product.');
            typeNotification.show();
        }
    
        checkEmptyFields();
    }
    
    $('#productType').change(updateFormFields);
    
        function checkEmptyFields() {
            var requiredInputs = $('input[required], select[required]');
            var valid = true;
    
            requiredInputs.each(function () {
                var notificationId = $(this).prop('id') + 'Notification';
                var notificationElement = $('#' + notificationId);
    
                if (!$(this).prop('value').trim()) {
                    notificationElement.text('Please insert ' + $(this).prop('name'));
                    notificationElement.show();
                    valid = false;
                } else {
                    notificationElement.text('');
                }
            });
    
            return valid;
        }
    
        $('#saveBtn').click(function(e) {
            e.preventDefault();
    
            if (checkEmptyFields()) {
                var sku = $('#sku').val().trim();
                checkDuplicateSKU(sku, function(isDuplicate) {
                    if (isDuplicate) {
                        $('#skuNotification').text('Choose another SKU, this one is already in use.');
                        $('#skuNotification').show();
                    } else {
                        $('#skuNotification').text('');
                        $.ajax({
                            url: 'add-product.php',
                            type: 'POST',
                            data: $('#product_form').serialize(),
                            success: function(response) {
                                if (response && response.success) {
                                    window.location.href = 'index.php';
                                } else {
                                    alert('Product addition failed: ' + (response.message || 'Unknown error'));
                                }
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.error('Error submitting form:', textStatus, errorThrown);
                                alert('Error submitting form. Please try again.');
                            }
                        });
                    }
                });
            }
        });
    
        function checkDuplicateSKU(sku, callback) {
            $.ajax({
                url: 'classes/ProductManager.php',
                type: 'POST',
                data: { sku: sku },
                success: function(response) {
                    if (response.duplicate) {
                        $('#skuNotification').text('Choose another SKU, this one is already in use.');
                        $('#skuNotification').show();
                    } else {
                        $('#skuNotification').text('');
                    }
                    callback(response.duplicate);
                },
                error: function(xhr, status, error) {
                    console.error('Error checking duplicate SKU:', error);
                    callback(true);
                }
            });
        }
        function massDelete() {
            var selectedSKUs = [];
            $('.delete-checkbox:checked').each(function () {
                selectedSKUs.push($(this).val());
            });
    
            if (selectedSKUs.length > 0) {
                $.ajax({
                    url: 'mass-delete.php',
                    type: 'POST',
                    data: { skus: selectedSKUs },
                    success: function (response) {
                        console.log(response.message);
    
                        if (response.success) {
                            location.reload();
                        } else {
                            alert('Failed to delete products.');
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error('Error during mass delete:', textStatus, errorThrown);
                    }
                });
            } else {
                console.log('No products selected for mass delete.');
            }
        }
    
        $('#massDeleteBtn').click(massDelete);
    
        console.log('script.js loaded');
    });
