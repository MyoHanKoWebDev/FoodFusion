document.addEventListener('DOMContentLoaded', function () {
    // Profile Dropdown
    const profileDropdownToggle = document.getElementById('profileDropdownToggle');
    const profileDropdown = document.getElementById('profileDropdown');

    profileDropdownToggle.addEventListener('click', function () {
        profileDropdown.style.display = profileDropdown.style.display === 'block' ? 'none' : 'block';
        profileDropdown.style.opacity = profileDropdown.style.opacity === '1' ? '0' : '1';
        profileDropdown.style.transform = profileDropdown.style.transform === 'translateY(0)' ? 'translateY(-10px)' : 'translateY(0)';
    });

    // Recipe Dropdown
    const recipeDropdownToggle = document.getElementById('recipeDropdownToggle');
    const recipeDropdown = document.getElementById('recipeDropdown');

    recipeDropdownToggle.addEventListener('click', function () {
        recipeDropdown.style.display = recipeDropdown.style.display === 'block' ? 'none' : 'block';
        recipeDropdown.style.opacity = recipeDropdown.style.opacity === '1' ? '0' : '1';
        recipeDropdown.style.transform = recipeDropdown.style.transform === 'translateY(0)' ? 'translateY(-10px)' : 'translateY(0)';
    });

    // Comments Dropdown
    const commentDropdownToggle = document.getElementById('commentDropdownToggle');
    const commentDropdown = document.getElementById('commentDropdown');

    commentDropdownToggle.addEventListener('click', function () {
        commentDropdown.style.display = commentDropdown.style.display === 'block' ? 'none' : 'block';
        commentDropdown.style.opacity = commentDropdown.style.opacity === '1' ? '0' : '1';
        commentDropdown.style.transform = commentDropdown.style.transform === 'translateY(0)' ? 'translateY(-10px)' : 'translateY(0)';
    });

    // Close dropdown if clicked outside
    window.addEventListener('click', function(event) {
        if (!event.target.closest('.user-profile') && profileDropdown.style.display === 'block') {
            profileDropdown.style.display = 'none';
        }
        if (!event.target.closest('#recipeDropdownToggle') && recipeDropdown.style.display === 'block') {
            recipeDropdown.style.display = 'none';
        }
        if (!event.target.closest('#commentDropdownToggle') && commentDropdown.style.display === 'block') {
            commentDropdown.style.display = 'none';
        }
    });

   

});


document.addEventListener('DOMContentLoaded', () => {
    const newRecipeLink = document.getElementById('newRecipeLink');
    const recipeFormSection = document.getElementById('recipeFormSection');
    const ingredientList = document.getElementById('ingredientList');

    // Show the recipe form when "New Recipe" is clicked
    newRecipeLink.addEventListener('click', (e) => {
        e.preventDefault();
        recipeFormSection.classList.toggle('hidden');
    });

    // Add event listener for dynamically adding/removing ingredients
    ingredientList.addEventListener('click', (e) => {
        if (e.target.classList.contains('add-ingredient-btn')) {
            addIngredientField();
        } else if (e.target.classList.contains('remove-ingredient-btn')) {
            removeIngredientField(e.target);
        }
    });

    function addIngredientField() {
        const newIngredient = document.createElement('div');
        newIngredient.classList.add('ingredient-item');
        newIngredient.innerHTML = `
            <input type="text" name="ingredient[]" placeholder="Ingredient name" required>
            <button type="button" class="add-ingredient-btn">Add</button>
            <button type="button" class="remove-ingredient-btn">Remove</button>
        `;
        ingredientList.appendChild(newIngredient);
    }

    function removeIngredientField(button) {
        const ingredientItem = button.closest('.ingredient-item');
        if (ingredientList.children.length > 1) {
            ingredientItem.remove();
        }
    }
 });

document.addEventListener('DOMContentLoaded', function() {
    // Event listeners for toggling sections
    const newRecipeLink = document.getElementById('newRecipeLink');
    const displayRecipeTable = document.getElementById('displayRecipeTable');
    const statBox1 = document.querySelector('.box1');
    const statBox2 = document.querySelector('.box2');
    const displayRating = document.getElementById('displayRating');
    // Select all nav-item links
    const navLinks = document.querySelectorAll('.nav-item a');

    // Add click event listener to all links
    navLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            // Remove 'active' class from all links
            navLinks.forEach(link => link.classList.remove('active'));

            // Add 'active' class to the clicked link
            this.classList.add('active');

            // Allow navigation for Main Dashboard
            if (this.getAttribute('href') === 'usersetting.php') {
                return; // Do nothing, let the browser navigate
            } else {
                event.preventDefault(); // Prevent default for other buttons
            }
        });
    });

    // Set active state based on the current page URL
    const currentUrl = window.location.href;

    // If on usersetting.php, highlight the Main Dashboard link
    if (currentUrl.includes('usersetting.php')) {
        document.querySelector('a[href="usersetting.php"]').classList.add('active');
    }

    if (newRecipeLink) {
        newRecipeLink.addEventListener('click', function() {
            // Toggle New Recipe form visibility
            toggleSection('newRecipeForm');
        });
    }

    if (displayRecipeTable) {
        displayRecipeTable.addEventListener('click', function() {
            // Toggle Recipe Table visibility
            toggleSection('recipeTableSection');
        });
    }

    // Adding event listeners to statBox1 and statBox2 for showing the same table
    if (statBox1) {
        statBox1.addEventListener('click', function() {
            toggleSection('recipeTableSection');
        });
    }

    if (statBox2) {
        statBox2.addEventListener('click', function() {
            toggleSection('recipeTableSection');
        });
    }

    if (displayRating) {
        displayRating.addEventListener('click', function() {
            // Toggle Rating Display Section visibility
            toggleSection('ratingDisplaySection');
        });
    }

    // Helper function to show one section and hide others
    function toggleSection(showId) {
        const sections = ['newRecipeForm', 'recipeTableSection', 'ratingDisplaySection'];
        
        sections.forEach(sectionId => {
            const section = document.getElementById(sectionId);
            if (sectionId === showId) {
                // Toggle visibility of the clicked section
                section.classList.toggle('hidden');
            } else {
                // Ensure other sections are hidden
                section.classList.add('hidden');
            }
        });
    }
});


// Animate the hover effect using JavaScript for smoother transitions
document.querySelectorAll('.menu-item').forEach(item => {
    item.addEventListener('mouseover', () => {
        item.classList.add('hovered');
    });
    
    item.addEventListener('mouseout', () => {
        item.classList.remove('hovered');
    });
});

