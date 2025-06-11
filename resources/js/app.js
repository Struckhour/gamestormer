// resources/js/app.js

import '../css/app.css'; // Keep your CSS import

document.addEventListener('DOMContentLoaded', () => {
    // Select all elements that act as collapsible headers
    const collapsibleHeaders = document.querySelectorAll('.js-collapsible-header');

    // Loop through each header and attach a click event listener
    collapsibleHeaders.forEach(header => {
        header.addEventListener('click', () => {
            // Find the content UL that immediately follows this header DIV
            const content = header.nextElementSibling;

            // Find the arrow SVG icon inside this header DIV
            const arrow = header.querySelector('.js-collapsible-arrow');

            // Ensure both content and arrow exist before trying to manipulate them
            if (content && arrow) {
                // Toggle the 'hidden' class on the content.
                // If 'hidden' is present, it removes it (shows content).
                // If 'hidden' is absent, it adds it (hides content).
                content.classList.toggle('hidden');

                // Toggle the 'rotate-90' class on the arrow for visual feedback
                // The 'transition-transform duration-200' class already on the SVG provides a smooth animation.
                arrow.classList.toggle('rotate-90');
            }
        });
    });

    // --- OPTIONAL ENHANCEMENT: Automatically open the active feature's parent branches ---
    // This part ensures that if a specific feature is active (e.g., from a direct link),
    // its parent department and subdepartment sections are automatically expanded.
    const activeFeatureElement = document.querySelector('.font-bold.text-indigo-700');
    if (activeFeatureElement) {
        let currentCollapsibleContent = activeFeatureElement.closest('.js-collapsible-content');
        while (currentCollapsibleContent) {
            // If the content block is currently hidden, remove the 'hidden' class to show it
            if (currentCollapsibleContent.classList.contains('hidden')) {
                currentCollapsibleContent.classList.remove('hidden');
            }

            // Find the header (DIV) that immediately precedes this content block
            const header = currentCollapsibleContent.previousElementSibling;

            // If a valid header is found and it's a collapsible header
            if (header && header.classList.contains('js-collapsible-header')) {
                // Find the arrow inside that header
                const arrow = header.querySelector('.js-collapsible-arrow');
                // Ensure the arrow is rotated down to indicate the section is open
                if (arrow) {
                    arrow.classList.add('rotate-90');
                }
            }

            // Move up to the next parent collapsible content block for the next iteration
            // This allows it to open nested sections (e.g., a department containing a subdepartment)
            currentCollapsibleContent = currentCollapsibleContent.parentElement.closest('.js-collapsible-content');
        }
    }
    // --- END OPTIONAL ENHANCEMENT ---
});
