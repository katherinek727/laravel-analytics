/**
 * Dynamic Field Display Controller
 * 
 * This script dynamically shows/hides form fields based on selected type.
 * Fields are shown only if their name attribute contains the selected type value.
 * 
 * Algorithm Explanation:
 * 1. Event-Driven Approach: Listens for change events on the type select element
 * 2. Attribute Filtering: Uses data-* attributes for metadata instead of parsing name
 * 3. CSS Class Toggling: Uses CSS classes for show/hide with transitions
 * 4. Debouncing: Prevents rapid re-renders during fast selection changes
 * 
 * Alternative Algorithms Considered:
 * 1. Name Parsing (Rejected): Parsing name attributes is fragile and error-prone
 * 2. Data Attributes (Chosen): More reliable, explicit, and maintainable
 * 3. CSS Attribute Selectors (Rejected): Limited browser support for complex selectors
 * 4. Virtual DOM (Overkill): Too heavy for this simple use case
 */

(function() {
    'use strict';

    // Configuration
    const CONFIG = {
        typeSelector: 'select[name="type"]',
        fieldContainer: '.form-fields',
        fieldSelector: '.form-field',
        typeDataAttribute: 'data-field-types',
        hiddenClass: 'field-hidden',
        visibleClass: 'field-visible',
        transitionDuration: 300,
        debounceDelay: 100
    };

    // State management
    let currentType = '';
    let debounceTimer = null;

    /**
     * Initialize the dynamic field controller
     */
    function init() {
        const typeSelect = document.querySelector(CONFIG.typeSelector);
        
        if (!typeSelect) {
            console.warn('Type select element not found. Dynamic fields disabled.');
            return;
        }

        // Set initial state
        currentType = typeSelect.value;
        updateFieldVisibility(currentType);

        // Add event listener with debouncing
        typeSelect.addEventListener('change', function(event) {
            const newType = event.target.value;
            
            // Debounce to prevent rapid updates
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                if (newType !== currentType) {
                    currentType = newType;
                    updateFieldVisibility(currentType);
                }
            }, CONFIG.debounceDelay);
        });

        // Add CSS for transitions
        addStyles();

        console.log('Dynamic field controller initialized');
    }

    /**
     * Update visibility of all fields based on selected type
     * @param {string} selectedType - The currently selected type value
     */
    function updateFieldVisibility(selectedType) {
        const fields = document.querySelectorAll(CONFIG.fieldSelector);
        let visibleCount = 0;

        fields.forEach(field => {
            const fieldTypes = getFieldTypes(field);
            const shouldShow = shouldDisplayField(fieldTypes, selectedType);

            if (shouldShow) {
                showField(field);
                visibleCount++;
            } else {
                hideField(field);
            }
        });

        // Optional: Update a counter or log
        if (CONFIG.debug) {
            console.log(`Showing ${visibleCount} fields for type: "${selectedType}"`);
        }
    }

    /**
     * Get the types associated with a field
     * @param {HTMLElement} field - The field element
     * @returns {Array} Array of type strings
     */
    function getFieldTypes(field) {
        const typesAttr = field.getAttribute(CONFIG.typeDataAttribute);
        
        if (!typesAttr) {
            // Fallback: parse name attribute (less reliable)
            const name = field.getAttribute('name') || '';
            return name.split('-').filter(Boolean);
        }

        return typesAttr.split(',').map(type => type.trim());
    }

    /**
     * Determine if a field should be displayed for the given type
     * @param {Array} fieldTypes - Types associated with the field
     * @param {string} selectedType - Currently selected type
     * @returns {boolean} True if field should be shown
     */
    function shouldDisplayField(fieldTypes, selectedType) {
        if (!selectedType || selectedType === 'all') {
            return true;
        }

        return fieldTypes.includes(selectedType);
    }

    /**
     * Show a field with animation
     * @param {HTMLElement} field - The field element to show
     */
    function showField(field) {
        field.classList.remove(CONFIG.hiddenClass);
        field.classList.add(CONFIG.visibleClass);
        
        // Ensure field is focusable
        const input = field.querySelector('input, select, textarea');
        if (input) {
            input.removeAttribute('disabled');
            input.removeAttribute('tabindex');
        }
    }

    /**
     * Hide a field with animation
     * @param {HTMLElement} field - The field element to hide
     */
    function hideField(field) {
        field.classList.remove(CONFIG.visibleClass);
        field.classList.add(CONFIG.hiddenClass);
        
        // Make field non-focusable
        const input = field.querySelector('input, select, textarea');
        if (input) {
            input.setAttribute('disabled', 'disabled');
            input.setAttribute('tabindex', '-1');
        }
    }

    /**
     * Add necessary CSS styles for transitions
     */
    function addStyles() {
        const styleId = 'dynamic-fields-styles';
        
        if (document.getElementById(styleId)) {
            return;
        }

        const style = document.createElement('style');
        style.id = styleId;
        style.textContent = `
            .${CONFIG.visibleClass} {
                display: block;
                opacity: 1;
                transform: translateY(0);
                transition: opacity ${CONFIG.transitionDuration}ms ease, 
                            transform ${CONFIG.transitionDuration}ms ease;
            }
            
            .${CONFIG.hiddenClass} {
                display: none;
                opacity: 0;
                transform: translateY(-10px);
                transition: opacity ${CONFIG.transitionDuration}ms ease, 
                            transform ${CONFIG.transitionDuration}ms ease;
            }
            
            .${CONFIG.hiddenClass} input,
            .${CONFIG.hiddenClass} select,
            .${CONFIG.hiddenClass} textarea {
                pointer-events: none;
            }
        `;

        document.head.appendChild(style);
    }

    /**
     * Public API
     */
    window.DynamicFields = {
        init,
        updateFieldVisibility,
        getCurrentType: () => currentType,
        showAllFields: () => updateFieldVisibility('all'),
        hideAllFields: () => updateFieldVisibility('')
    };

    // Auto-initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();