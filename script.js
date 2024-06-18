// HIDE ALL TABS
document.querySelectorAll("div[main] div.window-body").forEach(windows => {windows.style.display = "none";})

// For each tab button
document.querySelectorAll("menu[main] li a").forEach(tabButton => {
    // add a click listener
    tabButton.addEventListener("click", function() {
        // to de-highlight all tab buttons
        document.querySelectorAll("menu[main] li").forEach(allButtons => {allButtons.removeAttribute("aria-selected");});
        // leaving roon to highlight the clicked tab button
        tabButton.parentElement.setAttribute("aria-selected", "true");
        // at the same time hide all tabs (to end up closing the current one)
        document.querySelectorAll("div[main] div.window-body").forEach(allTabs => {allTabs.style.display = "none";});
        // to also leave room to show the current tab
        document.querySelector("div[main] div.window-body."+tabButton.getAttribute("href").replace(/#/g, "")).style.display = "block";
    });
});

// Get the fragment identifier from the URL
var fragment = window.location.hash.substr(1);
// Select the header based on the fragment identifier
var selectedHeader = document.querySelector("menu[main] li a[href=\"#"+fragment+"\"]");
// Check if the header exists and select it
if (selectedHeader) {
    selectedHeader.parentElement.setAttribute("aria-selected", "true");
}
// Select the content based on the fragment identifier
var selectedContent = document.querySelector("div[main] div.window-body."+fragment)
// Check if the content exists and unhide it
if (selectedContent) {
    selectedContent.style.display = "block"
}