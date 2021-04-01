<form action="<?= site_url('master/vendor/save') ?>" method="POST" id="form-vendor" enctype="multipart/form-data">
    <?= _csrf() ?>
    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Create New Vendor</h4>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="vendor">Vendor Name</label>
                        <input type="text" class="form-control" id="vendor" name="vendor" required maxlength="50"
                               value="<?= set_value('vendor') ?>" placeholder="Enter a vendor name">
                        <?= form_error('vendor') ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" maxlength="500" rows="3"
                                  placeholder="Additional info about the vendor"><?= set_value('description') ?></textarea>
                        <span class="form-text">Vendor additional information.</span>
                        <?= form_error('description') ?>
                    </div>
                </div>
            </div>
            <div id="location-wrapper">
            <?php if(set_value('locations')): ?>
                <?php foreach(set_value('locations', []) as $index => $vendorLocation): ?>
                <div class="row card-location">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="city_<?= $index ?>">City <?= $index + 1 ?></label>
                            <select class="form-control select2" name="locations[<?= $index ?>][city]" id="city_<?= $index ?>" data-placeholder="Select City of location" required>
                                <?php foreach ($cities as $city): ?>
                                    <option value="<?= $city ?>" <?= set_select('locations[<?= $index ?>][city]', $vendorLocation['city'], $city == $vendorLocation['city']) ?>>
                                        <?= $city ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?= form_error("locations[<?= $index ?>][city]") ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="office_phone_<?= $index ?>">Office Phone <?= $index + 1 ?></label>
                            <input type="text" class="form-control" id="office_phone_<?= $index ?>" name="locations[<?= $index ?>][office_phone]" required maxlength="50"
                                    value="<?= set_value("locations[<?= $index ?>][office_phone]", $vendorLocation['office_phone']) ?>" placeholder="Office phone number">
                            <?= form_error("locations[<?= $index ?>][office_phone]") ?>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="address_<?= $index ?>">Address <?= $index + 1 ?></label>
                            <textarea class="form-control addresses" id="address_<?= $index ?>" name="locations[<?= $index ?>][address]" maxlength="200" rows="2"
                                        placeholder="Full address"><?= set_value("locations[<?= $index ?>][address]", $vendorLocation['address']) ?></textarea>
                            <span class="form-text">
                                This location connected with <a href="https://maps.google.com/">Google Map</a>
                                from Google service, if your address does not come up just put in anyway.
                            </span>
                            <?= form_error("locations[<?= $index ?>][address]") ?>
                        </div>
                    </div>
                    <?php if($index>0): ?>
                    <div class="col-md-1">
                        <div class="form-group">
                            <button class="btn btn-sm btn-outline-danger btn-remove-location" type="button">
                                <i class="mdi mdi-trash-can-outline"></i>
                            </button>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            <?php endforeach ?>
            <?php else: ?>
                <div class="row card-location">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="city">City</label>
                            <select class="form-control select2" name="locations[0][city]" id="city_0" data-placeholder="Select City of location" required>
                                <?php foreach ($cities as $city): ?>
                                    <option value="<?= $city ?>" <?= set_select('locations[0][city]', $city) ?>>
                                        <?= $city ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?= form_error('locations[0][city]') ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="office_phone">Office phone</label>
                            <input type="text" class="form-control" id="office_phone_0" name="locations[0][office_phone]" required maxlength="50"
                                value="<?= set_value('locations[0][office_phone]') ?>" placeholder="City of location">
                            <?= form_error('locations[0][office_phone]') ?>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea class="form-control addresses" id="address" name="locations[0][address]" maxlength="200" rows="3"
                                    placeholder="Full address"><?= set_value('locations[0][address]') ?></textarea>
                            <span class="form-text">
                                This location connected with <a href="https://maps.google.com/">Google Map</a>
                                from Google service, if your address does not come up just put in anyway.
                            </span>
                            <?= form_error('locations[0][address]') ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <div class="text-right">
                <button class="btn btn-sm btn-info" id="btn-add-location" type="button">ADD LOCATION</button>
            </div>
            <div class="form-group">
                <label for="support">Support City</label>
                <select class="form-control select2" name="supports[]" id="support" data-placeholder="Select support" multiple>
                    <?php foreach ($cities as $city): ?>
                        <option value="<?= $city ?>" <?= set_select('supports[]', $city) ?>>
                            <?= $city ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="form-text">Multiple support city field allowed.</p>
                <?= form_error('support') ?>
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <select class="form-control select2" name="categories[]" id="category" data-placeholder="Select category" multiple required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>" <?= set_select('categories[]', $category['id']) ?>>
                            <?= $category['category'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="form-text">Multiple business field allowed.</p>
                <?= form_error('category') ?>
            </div>
            <div class="form-group">
                <label for="item_category">Item Category</label>
                <select class="form-control select2" name="item_categories[]" id="item_category" data-placeholder="Select item category" multiple required>
                    <?php foreach ($item_categories as $category): ?>
                        <option value="<?= $category['id'] ?>" <?= set_select('item_categories[]', $category['id']) ?>>
                            <?= $category['item_name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="form-text">Multiple item field allowed.</p>
                <?= form_error('item_category') ?>
            </div>
            <div class="form-group">
                <label for="facilities">Facilities</label>
                <textarea class="form-control" id="facilities" name="facilities" maxlength="200" rows="3"
                            placeholder="Additional info facilities about the vendor"><?= set_value('facilities') ?></textarea>
                <span class="form-text">Vendor additional facilities information.</span>
                <?= form_error('facilities') ?>
            </div>
            <div class="form-row">
                <div class="col-sm-12 col-lg-5">
                    <div class="form-row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="is_pkp_yes">Is PKP?</label>
                                <div class="form-row">
                                    <div class="col-5 col-md-6">
                                        <div class="form-check form-check-inline mt-2">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="is_pkp" id="is_pkp_yes" value="1"
                                                    <?= set_radio('is_pkp', 1) ?> required>
                                                YES
                                                <i class="input-helper"></i>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-5 col-md-6">
                                        <div class="form-check form-check-inline mt-2">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="is_pkp" id="is_pkp_no" value="0"
                                                    <?= set_radio('is_pkp', 0) ?>>
                                                NO
                                                <i class="input-helper"></i>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="has_tax_number_yes">Has Tax Number?</label>
                                <div class="form-row">
                                    <div class="col-5 col-md-6">
                                        <div class="form-check form-check-inline mt-2">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="has_tax_number" id="has_tax_number_yes" value="1"
                                                    <?= set_radio('has_tax_number', 1) ?> required>
                                                YES
                                                <i class="input-helper"></i>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-5 col-md-6">
                                        <div class="form-check form-check-inline mt-2">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="has_tax_number" id="has_tax_number_no" value="0"
                                                    <?= set_radio('has_tax_number', 0) ?>>
                                                NO
                                                <i class="input-helper"></i>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="form-group">
                        <label for="category">Tax Number</label>
                        <input type="text" class="form-control" id="tax_number" name="tax_number" <?= empty(set_value('has_tax_number', 0)) ? 'disabled' : 'required' ?> maxlength="50"
                               value="<?= set_value('tax_number') ?>" placeholder="Put tax number">
                        <?= form_error('tax_number') ?>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <div class="form-group">
                        <label for="tax_file">Tax File</label>
                        <input type="file" id="tax_file" name="tax_file" class="file-upload-default tax_file" data-max-size="3000000">
                        <div class="input-group">
                            <input type="text" class="form-control file-upload-info" disabled placeholder="Upload file">
                            <div class="input-group-append">
                                <button class="file-upload-browse btn btn-info btn-simple-upload tax_file" type="button" <?= empty(set_value('has_tax_number', 0)) ? 'disabled' : '' ?>>
                                    Pick
                                </button>
                            </div>
                        </div>
                        <?= form_error('tax_file') ?>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-sm-12 col-lg-5">
                    <div class="form-row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="business_type">Business</label>
                                <div class="form-row">
                                    <div class="col-5 col-md-6">
                                        <div class="form-check form-check-inline mt-2">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="business_type" id="business_type_perorangan" value="PERORANGAN"
                                                    <?= set_radio('business_type', 1) ?> required>
                                                Perorangan 
                                                <i class="input-helper"></i>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-5 col-md-6">
                                        <div class="form-check form-check-inline mt-2">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="business_type" id="business_type_perusahaan" value="PERUSAHAAN"
                                                    <?= set_radio('business_type', 0) ?>>
                                                Perusahaan
                                                <i class="input-helper"></i>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="is_active">Is Active?</label>
                                <span class="form-check ml-2">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input"<?= set_checkbox('is_active', 1, true); ?>
                                            id="is_active" name="is_active" value="1">
                                        YES
                                    </label>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="form-group">
                        <label for="is_owned_yes">Is Owned?</label>
                        <div class="form-row">
                            <div class="col-5 col-md-6">
                                <div class="form-check form-check-inline mt-2">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="is_owned" id="is_owned_yes" value="1"
                                            <?= set_radio('is_owned', 1) ?> required>
                                        YES
                                        <i class="input-helper"></i>
                                    </label>
                                </div>
                            </div>
                            <div class="col-5 col-md-6">
                                <div class="form-check form-check-inline mt-2">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="is_owned" id="is_owned_no" value="0"
                                            <?= set_radio('is_owned', 0) ?>>
                                        NO
                                        <i class="input-helper"></i>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Vendor Contacts</h4>
            <?= form_error('contacts[]') ?>

            <table class="table table-responsive" id="table-contact">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Type</th>
                    <th>Title</th>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Contact</th>
                    <th>Email<span style="color:gray; font-size:11px;"> (This field is not required)</span></th>
                    <th class="text-center">Primary</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php $vendorContacts = set_value('contacts', [
                        ['type' => 'WORK', 'title' => 'MR', 'name' => '','position' => 'FINANCE', 'contact' => '', 'email' => '', 'is_primary' => true],
                        ['type' => 'WORK', 'title' => 'MR', 'name' => '','position' => 'FINANCE', 'contact' => '', 'email' => '', 'is_primary' => false],
                ]); ?>
                <?php foreach ($vendorContacts as $index => $contact): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td>
                            <select class="custom-select" name="contacts[<?= $index ?>][type]">
                                <option value="WORK"<?= $contact['type'] == 'WORK' ? ' selected' : '' ?>>WORK</option>
                                <option value="MOBILE"<?= $contact['type'] == 'MOBILE' ? ' selected' : '' ?>>MOBILE</option>
                                <option value="PRIVATE"<?= $contact['type'] == 'PRIVATE' ? ' selected' : '' ?>>PRIVATE</option>
                                <option value="WHATSAPP"<?= $contact['type'] == 'WHATSAPP' ? ' selected' : '' ?>>WHATSAPP</option>
                                <option value="OTHER"<?= $contact['type'] == 'OTHER' ? ' selected' : '' ?>>OTHER</option>
                            </select>
                        </td>
                        <td>
                            <select class="custom-select" name="contacts[<?= $index ?>][title]">
                                <option value="MR"<?= $contact['title'] == 'MR' ? ' selected' : '' ?>>MR</option>
                                <option value="MRS"<?= $contact['title'] == 'MRS' ? ' selected' : '' ?>>MRS</option>
                            </select>
                        </td>
                        <td><input type="text" class="form-control" name="contacts[<?= $index ?>][name]" placeholder="Contact name" value="<?= $contact['name'] ?>" required></td>
                        <td>
                            <select class="custom-select" name="contacts[<?= $index ?>][position]">
                                <option value="FINANCE"<?= $contact['position'] == 'FINANCE' ? ' selected' : '' ?>>FINANCE</option>
                                <option value="MARKETING"<?= $contact['position'] == 'MARKETING' ? ' selected' : '' ?>>MARKETING</option>
                                <option value="MANAGEMENT"<?= $contact['position'] == 'MANAGEMENT' ? ' selected' : '' ?>>MANAGEMENT</option>
                                <option value="OWNER"<?= $contact['position'] == 'OWNER' ? ' selected' : '' ?>>OWNER</option>
                                <option value="OTHER"<?= $contact['position'] == 'OTHER' ? ' selected' : '' ?>>OTHER</option>
                            </select>
                        </td>
                        <td><input type="tel" class="form-control" name="contacts[<?= $index ?>][contact]" placeholder="Contact number" value="<?= $contact['contact'] ?>"></td>
                        <td><input type="email" class="form-control" name="contacts[<?= $index ?>][email]" placeholder="Email address" value="<?= $contact['email'] ?>"></td>
                        <td class="text-center">
                            <div class="form-check d-inline-block mt-0 mb-3 mx-auto">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="contacts[<?= $index ?>][is_primary]" id="contact_<?= $index ?>" value="1"<?= get_if_exist($contact, 'is_primary', 0) == 1 ? ' checked' : '' ?>>
                                    <i class="input-helper"></i>
                                </label>
                            </div>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-danger btn-delete" type="button">
                                <i class="mdi mdi-trash-can-outline"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer text-right">
            <button class="btn btn-sm btn-info" id="btn-add-contact" type="button">ADD CONTACT</button>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Vendor Banks</h4>
            <?= form_error('banks[]') ?>

            <table class="table table-md" id="table-bank">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Bank</th>
                    <th>Account Name</th>
                    <th>Account Number</th>
                    <th class="text-center">Primary</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php $vendorBanks = set_value('banks', [
                    ['bank' => '', 'account_number' => '', 'account_name' => '', 'is_primary' => true],
                    ['bank' => '', 'account_number' => '', 'account_name' => '', 'is_primary' => false],
                ]); ?>
                <?php foreach ($vendorBanks as $index => $bank): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><input type="text" class="form-control" name="banks[<?= $index ?>][bank]" placeholder="Bank name" value="<?= $bank['bank'] ?>" required></td>
                        <td><input type="text" class="form-control" name="banks[<?= $index ?>][account_name]" placeholder="Account name" value="<?= $bank['account_name'] ?>"></td>
                        <td><input type="text" class="form-control" name="banks[<?= $index ?>][account_number]" placeholder="Account number" value="<?= $bank['account_number'] ?>"></td>
                        <td class="text-center">
                            <div class="form-check d-inline-block mt-0 mb-3 mx-auto">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="banks[<?= $index ?>][is_primary]" id="bank_<?= $index ?>" value="1"<?= get_if_exist($bank, 'is_primary', 0) == 1 ? ' checked' : '' ?>>
                                    <i class="input-helper"></i>
                                </label>
                            </div>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-danger btn-delete" type="button">
                                <i class="mdi mdi-trash-can-outline"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer text-right">
            <button class="btn btn-sm btn-info" id="btn-add-bank" type="button">ADD BANK</button>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body d-flex justify-content-between">
            <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
            <button type="submit" class="btn btn-success" data-toggle="one-touch" data-touch-message="Saving...">Save Vendor</button>
        </div>
    </div>
</form>

<script>
    function initAutocomplete() {
        // Create the search box and link it to the UI element.
        var input = document.querySelector(".addresses");
        var searchBox = new google.maps.places.SearchBox(input);

        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function () {
            var places = searchBox.getPlaces();
            if (places.length == 0) {
                return;
            }
        });

        /*// limit city and country in Indonesia
        var countryRestrict = {'country': 'id'};
        var autocomplete = new google.maps.places.Autocomplete(
            /!** @type {!HTMLInputElement} *!/ (
                document.getElementById('city')), {
                types: ['(cities)'],
                componentRestrictions: countryRestrict
            });*/
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDg4esaS2P9dXK7ApOBTdXcnfBy2heCKhw&libraries=places&callback=initAutocomplete"
        async defer></script>
<script id="location-template" type="text/x-custom-template">
    <div class="row card-location">
        <div class="col-md-3">
            <div class="form-group">
                <label for="city_{{index}}">City {{no}}</label>
                <select class="form-control select2 city" name="locations[{{index}}][city]" id="city_{{index}}" data-placeholder="Select City of location" required>
                    <?php foreach ($cities as $city): ?>
                        <option value="<?= $city ?>" <?= set_select('locations[{{index}}][city]', $city) ?>>
                            <?= $city ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?= form_error('locations[{{index}}][city]') ?>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="office_phone_{{index}}">Office Phone {{no}}</label>
                <input type="text" class="form-control" id="office_phone_{{index}}" name="locations[{{index}}][office_phone]" required maxlength="50"
                        value="<?= set_value('locations[{{index}}][office_phone]') ?>" placeholder="Office phone number">
                <?= form_error('locations[{{index}}][office_phone]') ?>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label for="address_{{index}}">Address {{no}}</label>
                <textarea class="form-control addresses" id="address_{{index}}" name="locations[{{index}}][address]" maxlength="200" rows="2"
                            placeholder="Full address"><?= set_value('locations[{{index}}][address]') ?></textarea>
                <span class="form-text">
                    This location connected with <a href="https://maps.google.com/">Google Map</a>
                    from Google service, if your address does not come up just put in anyway.
                </span>
                <?= form_error('locations[{{index}}][address]') ?>
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group">
                <button class="btn btn-sm btn-outline-danger btn-remove-location" type="button">
                    <i class="mdi mdi-trash-can-outline"></i>
                </button>
            </div>
        </div>
    </div>
</script>
