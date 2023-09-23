document.addEventListener("DOMContentLoaded", function (event) {
	const components = document.getElementsByTagName('include-component');

	for (let component of components){
		fetch (component.getAttribute('href'))
		.then(response => response.text())
		.then(html => {
			component.innerHTML = html;
			const onload = new Function(['event'], component.getAttribute('onload'));
			onload({
				target: component
			});

		})
		.catch(err => console.error(err));
	}
});