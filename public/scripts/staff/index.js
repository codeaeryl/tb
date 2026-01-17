// sidebar variables //
const toggleButton = document.getElementById('toggle-btn')
const sidebar = document.getElementById('sidebar')

// open-close sidebar (desktop only) //
function toggleSidebar(){
  sidebar.classList.toggle('close')
  toggleButton.classList.toggle('rotate')
  // close all dropdowns
  closeAllSubMenus()
}
// toggle dropdown //
function toggleSubMenu(button) {
  // QoL for mobile users, so when clicking another dropdown, the previous is closed //
  // This code ensures that desktop can close the active dropdown//
  if (!button.nextElementSibling.classList.contains('show')){
    closeAllSubMenus()
  }
  // show div with list items //
  button.nextElementSibling.classList.toggle('show')
  button.classList.toggle('rotate')

  // if the sidebar is closed //
  if(sidebar.classList.contains('close')) {
    sidebar.classList.toggle('close')
    toggleButton.classList.toggle('rotate')
  }
}
// closes all dropdowns //
function closeAllSubMenus(){
  // for each elements in sidebar with .show //
  Array.from(sidebar.getElementsByClassName('show')).forEach(ul =>{
    ul.classList.remove('show')
    ul.previousElementSibling.classList.remove('rotate')
  })
}