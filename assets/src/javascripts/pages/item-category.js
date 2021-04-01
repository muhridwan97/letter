import variables from '../components/variables';

export default function () {
    const formSetCategory = $('#form-set-category');
    const selectCategory = formSetCategory.find('#category');
    const selectItemCategory = formSetCategory.find("#item_category");

    selectCategory.on('change', function () {
        const category = $(this).val();

        // populate item category by its category
        selectItemCategory.empty().append($('<option>', { value: '', selected: true }).text("-- Select Item Category --")).prop("disabled", true);
        fetch(`${variables.baseUrl}requisition/ajax-get-item-category?category=${category}`)
            .then(result => result.json())
            .then(data => {
                selectItemCategory.prop("disabled", false);
                data.forEach(row => {
                    selectItemCategory.append(
                        $('<option>', {value: row.id}).text(row.item_name)
                    );
                });
            })
            .catch(err => {
                selectItemCategory.prop("disabled", false);
            });

    });
};
