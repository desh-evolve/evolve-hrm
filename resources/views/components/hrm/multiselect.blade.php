<script>
    (function ($) {
        $.fn.multiSelector = function (options) {
            const settings = $.extend({
                title: 'Items',
                data: [], // Array of objects: { id: value, name: displayName }
                setSelected: [], // Array of already selected IDs
                onSelectionChange: null // Callback when selection changes
            }, options);
    
            const container = $(this);
    
            // Template with toggleable arrow and message
            const template = `
                <div class="multi-selector-header d-flex align-items-center mb-2">
                    <button type="button" class="btn btn-primary btn-sm toggle-arrow me-3">&#11167;</button>
                    <span class="selected-count">0 ${settings.title} Currently Selected, Click the arrow to modify.</span>
                </div>
                <div class="multi-selector-body row border p-2 col-md-12" style="display: none;">
                    <div class="select-box col-md-5">
                        <label class="form-label">Unselected</label>
                        <select class="form-select unselected-list" multiple size="5"></select>
                        <div class="mt-2 d-flex justify-content-between">
                            <button type="button" class="btn btn-sm btn-soft-dark select-all-unselected">Select All</button>
                            <button type="button" class="btn btn-sm btn-soft-dark unselect-all-unselected">Unselect All</button>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex justify-content-center align-items-center">
                        <div class="d-flex flex-column align-items-center">
                            <button type="button" class="btn btn-primary move-to-selected mb-2">&gt;&gt;</button>
                            <button type="button" class="btn btn-primary move-to-unselected">&lt;&lt;</button>
                        </div>
                    </div>
                    <div class="select-box col-md-5">
                        <label class="form-label">Selected</label>
                        <select class="form-select selected-list" multiple size="5"></select>
                        <div class="mt-2 d-flex justify-content-between">
                            <button type="button" class="btn btn-sm btn-soft-dark select-all-selected">Select All</button>
                            <button type="button" class="btn btn-sm btn-soft-dark unselect-all-selected">Unselect All</button>
                        </div>
                    </div>
                </div>
            `;
    
            container.html(template);
    
            const multiSelectorBody = container.find('.multi-selector-body');
            const toggleArrow = container.find('.toggle-arrow');
            const selectedCount = container.find('.selected-count');
            const unselectedList = container.find('.unselected-list');
            const selectedList = container.find('.selected-list');
    
            // Separate data into selected and unselected based on setSelected
            settings.data.forEach(item => {
                if (settings.setSelected.includes(item.id)) {
                    selectedList.append(`<option value="${item.id}">${item.name}</option>`);
                } else {
                    unselectedList.append(`<option value="${item.id}">${item.name}</option>`);
                }
            });
    
            const updateSelection = () => {
                const selectedIds = selectedList.find('option').map(function () {
                    return $(this).val();
                }).get();
    
                // Update count message
                selectedCount.text(`${selectedIds.length} ${settings.title} Currently Selected, Click the arrow to modify.`);
    
                // Trigger callback
                if (typeof settings.onSelectionChange === 'function') {
                    settings.onSelectionChange(selectedIds);
                }
            };
    
            // Initialize with already selected items
            updateSelection();
    
            // Toggle show/hide of the multi-selector
            toggleArrow.on('click', function () {
                const isVisible = multiSelectorBody.is(':visible');
                multiSelectorBody.toggle();
                toggleArrow.html(isVisible ? '&#11167;' : '&#11165;'); // Update arrow direction
            });
    
            // Move to selected
            container.find('.move-to-selected').on('click', function () {
                unselectedList.find('option:selected').appendTo(selectedList);
                updateSelection();
            });
    
            // Move to unselected
            container.find('.move-to-unselected').on('click', function () {
                selectedList.find('option:selected').appendTo(unselectedList);
                updateSelection();
            });
    
            // Select All in Unselected
            container.find('.select-all-unselected').on('click', function () {
                unselectedList.find('option').prop('selected', true);
            });
    
            // Unselect All in Unselected
            container.find('.unselect-all-unselected').on('click', function () {
                unselectedList.find('option').prop('selected', false);
            });
    
            // Select All in Selected
            container.find('.select-all-selected').on('click', function () {
                selectedList.find('option').prop('selected', true);
            });
    
            // Unselect All in Selected
            container.find('.unselect-all-selected').on('click', function () {
                selectedList.find('option').prop('selected', false);
            });
    
            return this;
        };
    })(jQuery);
    </script>
    