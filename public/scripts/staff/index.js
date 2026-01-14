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
// dark mode variables//
const r = document.querySelector(':root');
const modeIcon = document.getElementById('mode-path')
const modeName = document.getElementById('mode-name')
const home=document.getElementById('home')
function onloadFunction() {
  /*first time loading*/
  if (localStorage.getItem('mode') === null) {
    localStorage.setItem('mode','dark')
  }
  /*if light mode*/
  if (localStorage.getItem('mode')==='light') {
    r.classList.add('dark')
  }
  toggleMode()
}
/*change theme*/
function toggleMode() {
  r.classList.toggle('dark')
  /*if dark theme */
  if (r.classList.contains('dark')) {
    modeIcon.setAttribute('d','M480-120q-150 0-255-105T120-480q0-150 105-255t255-105q14 0 27.5 1t26.5 3q-41 29-65.5 75.5T444-660q0 90 63 153t153 63q55 0 101-24.5t75-65.5q2 13 3 26.5t1 27.5q0 150-105 255T480-120Zm0-80q88 0 158-48.5T740-375q-20 5-40 8t-40 3q-123 0-209.5-86.5T364-660q0-20 3-40t8-40q-78 32-126.5 102T200-480q0 116 82 198t198 82Zm-10-270Z')
    modeName.innerHTML="Dark Mode"
    localStorage.setItem('mode','dark')
  /*if light theme*/
  } else {
    modeIcon.setAttribute('d','M480-360q50 0 85-35t35-85q0-50-35-85t-85-35q-50 0-85 35t-35 85q0 50 35 85t85 35Zm0 80q-83 0-141.5-58.5T280-480q0-83 58.5-141.5T480-680q83 0 141.5 58.5T680-480q0 83-58.5 141.5T480-280ZM200-440H40v-80h160v80Zm720 0H760v-80h160v80ZM440-760v-160h80v160h-80Zm0 720v-160h80v160h-80ZM256-650l-101-97 57-59 96 100-52 56Zm492 496-97-101 53-55 101 97-57 59Zm-98-550 97-101 59 57-100 96-56-52ZM154-212l101-97 55 53-97 101-59-57Zm326-268Z')
    modeName.innerHTML="Light Mode"
    localStorage.setItem('mode','light')
  }
}
/*check mode on load*/
onloadFunction()