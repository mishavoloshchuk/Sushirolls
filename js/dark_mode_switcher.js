let darkMode = false;
if (sessionStorage.getItem("dark_mode") !== null) {
	sessionStorage.getItem("dark_mode") == 'true' && switchDarkMode();
}
checkClientTheme(); // Init checking
function checkClientTheme(){
	if (sessionStorage.getItem("dark_mode") !== null) return;
	if (isCliendChangedTheme()) {
		darkMode = !darkMode;
		document.documentElement.setAttribute('dark-mode', darkMode);
	}
	requestAnimationFrame(checkClientTheme);
}

function switchDarkMode(){
	darkMode = !darkMode;
	sessionStorage.setItem("dark_mode", darkMode);
	document.documentElement.setAttribute('dark-mode', darkMode);
}

function isCliendChangedTheme(){
	return matchMedia("(prefers-color-scheme: dark)").matches !== darkMode;
}