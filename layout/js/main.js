var months = ['' ,'Jan','Feb','Mar','Apr', 'May', 'Jun','Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
function getLastMonth(cont, arrays){
	if(cont > 0){
		var today = new Date();
		var current = today.getMonth() + 1;
		var newArray = [];
		for(i = 1; i <= cont; i++){
			if(current > 0){
				var man = arrays[current];
				newArray.push(man);
				current--;
			}
			else if(current == 0){
				current = 12;
				var man = arrays[current];
				newArray.push(man);
				current--;
			}
		}
		return newArray.reverse();
	}
	else if(cont < 1){
		return "none";
	}
}
// Charts
//

'use strict';

var Charts = (function() {

	// Variable

	var $toggle = $('[data-toggle="chart"]');
	var mode = 'light';//(themeMode) ? themeMode : 'light';
	var fonts = {
		base: 'Open Sans'
	}



	// Colors
	var colors = {
		gray: {
			100: '#f6f9fc',
			200: '#e9ecef',
			300: '#dee2e6',
			400: '#ced4da',
			500: '#adb5bd',
			600: '#8898aa',
			700: '#525f7f',
			800: '#32325d',
			900: '#212529'
		},
		theme: {
			'default': '#172b4d',
			'primary': '#5e72e4',
			'secondary': '#f4f5f7',
			'info': '#11cdef',
			'success': '#2dce89',
			'danger': '#f5365c',
			'warning': '#fb6340'
		},
		black: '#12263F',
		white: '#FFFFFF',
		transparent: 'transparent',
	};


	// Methods
	

	// Chart.js global options
	function chartOptions() {

		// Options
		var options = {
			defaults: {
				global: {
					responsive: true,
					maintainAspectRatio: false,
					defaultColor: (mode == 'dark') ? colors.gray[700] : colors.gray[600],
					defaultFontColor: (mode == 'dark') ? colors.gray[700] : colors.gray[600],
					defaultFontFamily: fonts.base,
					defaultFontSize: 18,
					layout: {
						padding: 0
					},
					legend: {
						display: true,
						position: 'bottom',
						labels: {
							usePointStyle: true,
							padding: 16
						}
					},
					elements: {
						point: {
							radius: 7,
							backgroundColor: colors.theme['danger']
						},
						line: {
							tension: .4,
							borderWidth: 8,
							borderColor: colors.theme['primary'],
							backgroundColor: colors.transparent,
							borderCapStyle: 'rounded'
						},
						rectangle: {
							backgroundColor: colors.theme['warning']
						},
						arc: {
							backgroundColor: colors.theme['primary'],
							borderColor: (mode == 'dark') ? colors.gray[800] : colors.white,
							borderWidth: 4
						}
					},
					tooltips: {
						enabled: true,
						mode: 'index',
						intersect: false,
					}
				},
				doughnut: {
					cutoutPercentage: 83,
					legendCallback: function(chart) {
						var data = chart.data;
						var content = '';

						data.labels.forEach(function(label, index) {
							var bgColor = data.datasets[0].backgroundColor[index];

							content += '<span class="chart-legend-item">';
							content += '<i class="chart-legend-indicator" style="background-color: ' + bgColor + '"></i>';
							content += label;
							content += '</span>';
						});

						return content;
					}
				}
			}
		}

		// yAxes
		Chart.scaleService.updateScaleDefaults('linear', {
			gridLines: {
				borderDash: [2],
				borderDashOffset: [2],
				color: (mode == 'dark') ? colors.gray[900] : colors.gray[300],
				drawBorder: true,
				drawTicks: true,
				drawOnChartArea: true,
				zeroLineWidth: 0,
				zeroLineColor: 'rgba(0,0,0,0)',
				zeroLineBorderDash: [2],
				zeroLineBorderDashOffset: [2]
			},
			ticks: {
				beginAtZero: true,
				padding: 10,
				callback: function(value) {
					if (!(value % 10)) {
						return value
					}
				}
			}
		});

		// xAxes
		Chart.scaleService.updateScaleDefaults('category', {
			gridLines: {
				drawBorder: true,
				drawOnChartArea: true,
				drawTicks: true
			},
			ticks: {
				padding: 20
			},
			maxBarThickness: 20
		});

		return options;

	}

	// Parse global options
	function parseOptions(parent, options) {
		for (var item in options) {
			if (typeof options[item] !== 'object') {
				parent[item] = options[item];
			} else {
				parseOptions(parent[item], options[item]);
			}
		}
	}

	// Push options
	function pushOptions(parent, options) {
		for (var item in options) {
			if (Array.isArray(options[item])) {
				options[item].forEach(function(data) {
					parent[item].push(data);
				});
			} else {
				pushOptions(parent[item], options[item]);
			}
		}
	}

	// Pop options
	function popOptions(parent, options) {
		for (var item in options) {
			if (Array.isArray(options[item])) {
				options[item].forEach(function(data) {
					parent[item].pop();
				});
			} else {
				popOptions(parent[item], options[item]);
			}
		}
	}

	// Toggle options
	function toggleOptions(elem) {
		var options = elem.data('add');
		var $target = $(elem.data('target'));
		var $chart = $target.data('chart');

		if (elem.is(':checked')) {

			// Add options
			pushOptions($chart, options);

			// Update chart
			$chart.update();
		} else {

			// Remove options
			popOptions($chart, options);

			// Update chart
			$chart.update();
		}
	}

	// Update options
	function updateOptions(elem) {
		var options = elem.data('update');
		var $target = $(elem.data('target'));
		var $chart = $target.data('chart');

		// Parse options
		parseOptions($chart, options);

		// Toggle ticks
		toggleTicks(elem, $chart);

		// Update chart
		$chart.update();
	}

	// Toggle ticks
	function toggleTicks(elem, $chart) {

		if (elem.data('prefix') !== undefined || elem.data('prefix') !== undefined) {
			var prefix = elem.data('prefix') ? elem.data('prefix') : '';
			var suffix = elem.data('suffix') ? elem.data('suffix') : '';

			// Update ticks
			$chart.options.scales.yAxes[0].ticks.callback = function(value) {
				if (!(value % 10)) {
					return prefix + value + suffix;
				}
			}

			// Update tooltips
			$chart.options.tooltips.callbacks.label = function(item, data) {
				var label = data.datasets[item.datasetIndex].label || '';
				var yLabel = item.yLabel;
				var content = '';

				if (data.datasets.length > 1) {
					content += '' + label + '';
				}

				content += '' + prefix + yLabel + suffix + '';
				return content;
			}

		}
	}


	// Events

	// Parse global options
	if (window.Chart) {
		parseOptions(Chart, chartOptions());
	}

	// Toggle options
	$toggle.on({
		'change': function() {
			var $this = $(this);

			if ($this.is('[data-add]')) {
				toggleOptions($this);
			}
		},
		'click': function() {
			var $this = $(this);

			if ($this.is('[data-update]')) {
				updateOptions($this);
			}
		}
	});


	// Return

	return {
		colors: colors,
		fonts: fonts,
		mode: mode
	};

})();



//
// Bars chart
//

var BarsChart = (function() {

	//
	// Variables
	//

	var $chart = $('#chart-bars');


	//
	// Methods
	//

	// Init chart
	function initChart($chart) {

		// Create chart
		var ordersChart = new Chart($chart, {
			type: 'bar',
			data: {
				labels: getLastMonth(7, months),
				datasets: [{
					label: 'Sales',
					data: ordersData
				}]
			}
		});

		// Save to jQuery object
		$chart.data('chart', ordersChart);
	}


	// Init chart
	if ($chart.length) {
		initChart($chart);
	}

})();

'use strict';

//
// Sales chart
//

var SalesChart = (function() {

// Variables

var $chart = $('#chart-sales-dark');


// Methods

function init($chart) {

    var salesChart = new Chart($chart, {
    type: 'line',
    options: {
        scales: {
        	yAxes: [{
				gridLines: {
					lineWidth: 1,
					color: Charts.colors.gray[900],
					zeroLineColor: Charts.colors.gray[900]
				},
				ticks: {
					callback: function(value) {
						if (!(value % 10)) {
							return 'EGP ' + value;
						}
				}
				}
        	}]
        	},
        tooltips: {
            callbacks: {
                label: function(item, data) {
                    var label = data.datasets[item.datasetIndex].label || '';
                    var yLabel = item.yLabel;
                    var content = '';

                if (data.datasets.length > 1) {
                    content += '' + label + '';
                }

            content += 'EGP ' + yLabel;
            return content;
            }
        }
        }
    },
    data: {
        labels: getLastMonth(7, months),
        datasets: [{
        label: 'Sales',
        data: salesMonths
        }]
    }
    });

    // Save to jQuery object

    $chart.data('chart', salesChart);

};


// Events

if ($chart.length) {
    init($chart);
}

})();

//end here



// switch style 

'use strict';

function changeClass(object,oldClass,newClass){
	$(object).removeClass(oldClass).addClass(newClass);
}

'use strict';
function night(){
	changeClass("body","light-mode","dark-mode");
}
function light(){
	changeClass("body","dark-mode","light-mode");
}

function setMode(mode){
	if(mode == 'night'){
		night();
	}
	if(mode == 'light'){
		light();
	}
}

//
//  login page
//

const sign_in_btn = document.querySelector("#sign-in-btn");
const sign_up_btn = document.querySelector("#sign-up-btn");
const container = document.querySelector(".container");
try{
	sign_up_btn.addEventListener('click', () => {
		container.classList.add("sign-up-mode");
	});
	sign_in_btn.addEventListener('click', () => {
		container.classList.remove("sign-up-mode");
	});
}
catch(err){
	let er = err;
}

//
// pop up
//
const le = document.querySelector(".le");
const box_pop_up = document.querySelector(".box-pop-up");
const closeBtn = document.querySelector(".close-icon");
const cancelBtn = document.querySelector(".cancel");
const addBtn = document.querySelector("#add-btn");
const updateBtn = document.querySelector(".update");
const pop_up = document.querySelector(".pop-up");
const removeBtn = document.querySelector(".remove");
const noBtn = document.querySelector(".no");

try{
	closeBtn.addEventListener('click', () => {
		le.classList.remove("show");
		le.classList.add("hide");
		box_pop_up.classList.remove("show");
		box_pop_up.classList.add("hide");
	});
	cancelBtn.addEventListener('click', () => {
		le.classList.remove("show");
		le.classList.add("hide");
		box_pop_up.classList.remove("show");
		box_pop_up.classList.add("hide");
	});
	addBtn.addEventListener('click', () => {
		le.classList.remove("hide");
		le.classList.add("show");
		box_pop_up.classList.remove("hide");
		box_pop_up.classList.add("show");
	});
	updateBtn.addEventListener('click', () => {
		le.classList.remove("hide");
		le.classList.add("show");
		box_pop_up.classList.remove("hide");
		box_pop_up.classList.add("show");
	});
	le.addEventListener('click', () => {
		le.classList.remove("show");
		le.classList.add("hide");
		box_pop_up.classList.remove("show");
		box_pop_up.classList.add("hide");
		pop_up.classList.remove("show");
		pop_up.classList.add("hide");
	});
	removeBtn.addEventListener('click', () => {
		le.classList.remove("hide");
		le.classList.add("show");
		pop_up.classList.remove("hide");
		pop_up.classList.add("show");
	});
	noBtn.addEventListener('click', () => {
		le.classList.remove("show");
		le.classList.add("hide");
		pop_up.classList.remove("show");
		pop_up.classList.add("hide");
	});
}
catch(err){
	let er = err;
}


try{
	$(document).ready(function () {
		var $selectAll = $('#selectall'); // main checkbox inside table thead
		var $table = $('table'); // table selector 
		var $tdCheckbox = $table.find('td input:checkbox'); // checboxes inside table body
		var tdCheckboxChecked = 0; // checked checboxes
	
		// Select or deselect all checkboxes depending on main checkbox change
		$selectAll.on('click', function () {
			$tdCheckbox.prop('checked', this.checked);
		});
	
		// Toggle main checkbox state to checked when all checkboxes inside tbody tag is checked
		$tdCheckbox.on('change', function (e) {
			tdCheckboxChecked = $table.find('td input:checkbox:checked').length; // Get count of checkboxes that is checked
			// if all checkboxes are checked, then set property of main checkbox to "true", else set to "false"
			$selectAll.prop('checked', (tdCheckboxChecked === $tdCheckbox.length));
		});
	});
}
catch(err){
	let er = err;
}

try{
	var x, i, j, l, ll, selElmnt, a, b, c;
	x = document.getElementsByClassName('select-custom');
	l = x.length;
	for(i=0; i<l; i++){
		selElmnt = x[i].getElementsByTagName('select')[0];
		ll = selElmnt.length;
		a = document.createElement("DIV");
		a.setAttribute("class", "select-selected");
		a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
		x[i].appendChild(a);

		b = document.createElement("DIV");
		b.setAttribute("class", "select-items select-hide");
		for(j=1; j < ll; j++){
			c = document.createElement("DIV");
			c.innerHTML = selElmnt.options[j].innerHTML;
			c.addEventListener("click", function(e){
				var y, i, k, s, h, sl, yl, s = this.parentNode.parentNode.getElementsByTagName("select")[0];
				sl = s.length;
				h = this.parentNode.previousSibling;
				for(i = 0; i < sl; i++){
					if(s.options[i].innerHTML == this.innerHTML){
						s.selectedIndex = i;
						h.innerHTML = this.innerHTML;
						y = this.parentNode.getElementsByClassName("same-as-selected");
						yl = y.length;
						for(k=0; k < yl; k++){
							y[k].removeAttribute("class");
						}
						this.setAttribute("class", "same-as-selected");
						break;
					}
				}
				h.click();
			});
			b.appendChild(c);
		}
		x[i].appendChild(b);
		a.addEventListener("click", function(e){
			e.stopPropagation();
			closeAllSelect(this);
			this.nextSibling.classList.toggle("select-arrow-active");
			this.nextSibling.classList.toggle("select-hide");
		});
	}

	function closeAllSelect(elmnt){
		var x, y, i, xl, yl, arrNo = [];
		x = document.getElementsByClassName("select-items");
		y = document.getElementsByClassName("select-selected");
		xl = x.length;
		yl = y.length;
		for(i=0; i < yl; i++){
			if(elmnt == y[i]){
				arrNo.push(i);
			}else{
				y[i].classList.remove("select-arrow-active");
			}
		}
		for(i = 0; i < xl; i++){
			if(arrNo.indexOf(i)){
				x[i].classList.add("select-hide");
			}
		}
	}

	document.addEventListener("click", closeAllSelect);
}
catch(err){
	var er = err;
}

// 
// Required Field
// 

$(function(){

	'use strict';
	
	/* $('input').each(function(){
		if($(this).attr('required')){
			$(this).after("<span class='req text-danger'>*</span>");
			$(this).parent().addClass('input-required');
		}
	}); */
});