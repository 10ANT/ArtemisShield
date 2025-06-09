(function() {
	"use strict";

	//**<---- Dashboard E-Commerce Charts ---->**//

	/* Top Sales Chart JS*/
	const getTopSalesId = document.getElementById('top_sales');
	if (getTopSalesId) {
		var options = {
			series: [
				{
					name: "Current Sale",
					data: [35, 50, 55, 60, 50, 60, 55, 60, 78, 40, 95, 80]
				},
				{
					name: "Last Year Sale",
					data: [70, 50, 40, 40, 62, 52, 80, 40, 60, 53, 70, 70]
				}
			],
			chart: {
				type: "area",
				height: 366,
				zoom: {
					enabled: false
				}
			},
			colors: [
				"#605DFF", "#DDE4FF"
			],
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: "smooth",
				width: [2, 2, 0],
				dashArray: [0, 6, 0],
			},
			grid: {
				borderColor: "#ffffff"
			},
			fill: {
				type: 'gradient',
				gradient: {
					stops: [0, 90, 100],
					shadeIntensity: 1,
					opacityFrom: 0,
					opacityTo: 0.5
				}
			},
			xaxis: {
				categories: [
					"Jan",
					"Feb",
					"Mar",
					"Apr",
					"May",
					"Jun",
					"Jul",
					"Aug",
					"Sep",
					"Oct",
					"Nov",
					"Dec"
				],
				axisTicks: {
					show: false,
					color: '#B1BBC8'
				},
				axisBorder: {
					show: false,
					color: '#B1BBC8'
				},
				labels: {
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 5,
				max: 100,
				min: 0,
				labels: {
					formatter: (val) => {
						return '$' + val / 1 + 'K'
					},
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'left',
				fontFamily: "Inter",
				fontWeight: 400,
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			},
			tooltip: {
				y: {
					formatter: function(val) {
					return "$" + val + "k";
					}
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#top_sales"), options);
		chart.render();
	}
    
    /* Total Orders JS*/
	const getTotalOrders1Id = document.getElementById('total_orders1');
	if (getTotalOrders1Id) {
		var options = {
			series: [
				{
					name: "Completed",
					data: [80, 55, 60, 55, 80]
				},
				{
					name: "Pending",
					data: [50, 85, 60, 70, 60]
				}
			],
			chart: {
				type: "bar",
				height: 99,
				toolbar: {
					show: false
				}
			},
			colors: [
				"#1F64F1", "#C2CDFF"
			],
			plotOptions: {
				bar: {
					columnWidth: "85%"
				}
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				width: 2,
				show: true,
				colors: ["transparent"]
			},
			grid: {
				borderColor: "#ffffff"
			},
			xaxis: {
				categories: [
					"Jan",
					"Feb",
					"Mar",
					"Apr",
					"May"
				],
				axisTicks: {
					show: false,
					color: '#B1BBC8'
				},
				axisBorder: {
					show: false,
					color: '#B1BBC8'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				show: false,
				labels: {
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				}
			},
			tooltip: {
				y: {
					formatter: function(val) {
						return val + "%";
					}
				}
			},
			legend: {
				show: false,
				fontSize: '12px',
				position: 'bottom',
				horizontalAlign: 'center',
				fontFamily: "Inter",
				fontWeight: 400,
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					shape: "diamond",
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#total_orders1"), options);
		chart.render();
	}

    /* Total Customers JS*/
	const getTotalCustomersId = document.getElementById('total_customers');
	if (getTotalCustomersId) {
		var options = {
			series: [
				{
					name: "Orders",
					data: [55, 50, 60, 45, 85, 80, 100]
				},
				{
					name: "Sales",
					data: [45, 38, 80, 65, 55, 75, 90]
				}
			],
			chart: {
				height: 150,
				type: "line",
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			dataLabels: {
				enabled: false
			},
			colors: [
				"#605DFF", "#C2CDFF"
			],
			stroke: {
				width: 2,
				curve: "straight"
			},
			grid: {
				borderColor: "#ffffff"
			},
			xaxis: {
				categories: [
					"01 Jun",
					"02 Jun",
					"03 Jun",
					"04 Jun",
					"05 Jun",
					"06 Jun",
					"07 Jun"
				],
				axisTicks: {
					show: false,
					color: '#B1BBC8'
				},
				axisBorder: {
					show: false,
					color: '#B1BBC8'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				show: false,
				labels: {
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				}
			},
			legend: {
				show: false,
				fontSize: '12px',
				position: 'bottom',
				horizontalAlign: 'center',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					shape: "diamond",
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#total_customers"), options);
		chart.render();
	}

    /* Total Revenue JS*/
	const getTotalRevenueId = document.getElementById('total_evenue');
	if (getTotalRevenueId) {
		var options = {
			series: [
				{
					name: "Fashion",
					data: [20, 40, 25, 60, 30, 50]
				},
					{
					name: "Others",
					data: [20, 20, 25, 15, 35, 25]
				}
			],
			chart: {
				type: "bar",
				height: 100,
				stacked: true,
				toolbar: {
					show: false
				},
				zoom: {
					enabled: true
				}
			},
			plotOptions: {
				bar: {
					columnWidth: "55%"
				}
			},
			colors: [
				"#605DFF", "#C2CDFF"
			],
			grid: {
				borderColor: "#ffffff"
			},
			stroke: {
				width: 2,
				show: true,
				colors: ["transparent"]
			},
			dataLabels: {
				enabled: false
			},
			xaxis: {
				categories: [
					"Jan",
					"Feb",
					"Mar",
					"Apr",
					"May",
					"Jun"
				],
				axisTicks: {
					show: false,
					color: '#B1BBC8'
				},
				axisBorder: {
					show: false,
					color: '#B1BBC8'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				show: false,
				labels: {
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				}
			},
			legend: {
				show: false,
				fontSize: '12px',
				position: 'bottom',
				horizontalAlign: 'center',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					shape: "diamond",
				}
			},
			fill: {
				opacity: 1
			}
		};
		var chart = new ApexCharts(document.querySelector("#total_evenue"), options);
		chart.render();
	}

    /* Order Summery JS*/
	const getOrderSummeryId = document.getElementById('order_summery');
	if (getOrderSummeryId) {
		var options = {
			series: [60, 30, 10],
			chart: {
				height: 296,
				type: "donut"
			},
			labels: [
				"Completed", "New Order", "Pending"
			],
			colors: [
				"#37D80A", "#605DFF", "#AD63F6"
			],
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					shape: "diamond", // circle, square, line, plus, cross, diamond
				}
			},
			dataLabels: {
				enabled: false
			}
		};
		var chart = new ApexCharts(document.querySelector("#order_summery"), options);
		chart.render();
	}

    /* Returning Customer Rate JS*/
	const getReturningCustomerRateId = document.getElementById('returning_customer_rate');
	if (getReturningCustomerRateId) {
		var options = {
			series: [
				{
					name: "Fifth Time",
					data: [70, 23, 40, 30, 62, 52, 90, 20, 60, 53, 65, 70]
				},
				{
					name: "Fourth Time",
					data: [15, 58, 45, 38, 70, 50, 55, 60, 78, 40, 35, 35]
				}
			],
			chart: {
				height: 300,
				type: "line",
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			dataLabels: {
				enabled: false
			},
			colors: [
				"#605DFF", "#AD63F6"
			],
			stroke: {
				curve: "smooth",
				width: 2,
			},
			grid: {
				borderColor: "#F6F7F9"
			},
			markers: {
				size: 4,
				strokeWidth: 0,
				shape: ["circle", "square"],
				hover: {
					size: 5
				}
			},
			xaxis: {
				categories: [
					"Jan",
					"Feb",
					"Mar",
					"Apr",
					"May",
					"Jun",
					"Jul",
					"Aug",
					"Sep",
					"Oct",
					"Nov",
					"Dec"
				],
				axisTicks: {
					show: false,
					color: '#B1BBC8'
				},
				axisBorder: {
					show: false,
					color: '#B1BBC8'
				},
				labels: {
					show: true,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 5,
				max: 100,
				min: 0,
				labels: {
					formatter: (val) => {
						return val + '%'
					},
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				}
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					shape: "diamond",
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#returning_customer_rate"), options);
		chart.render();
	}


	//**<---- Dashboard CRM Charts ---->**//

	/* Revenue Growth JS*/
	const getRevenueGrowthId = document.getElementById('revenue_growth');
	if (getRevenueGrowthId) {
		var options = {
			series: [
				{
					name: "Revenue Growth",
					data: [3, 7, 7, 10, 9, 11, 15]
				}
			],
			chart: {
				type: "area",
				height: 95,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			colors: [
				"#3584FC"
			],
			dataLabels: {
				enabled: false
			},
			grid: {
				show: false,
				borderColor: "#ECEEF2"
			},
			stroke: {
				curve: "straight",
				width: 1
			},
			xaxis: {
				categories: [
					"Mon",
					"Tue",
					"Wed",
					"Thu",
					"Fri",
					"Sat",
					"Sun"
				],
				axisTicks: {
					show: false,
					color: '#B1BBC8'
				},
				axisBorder: {
					show: false,
					color: '#B1BBC8'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				// tickAmount: 6,
				show: false,
				// max: 150,
				// min: 0,
				labels: {
					formatter: (val) => {
						return '$' + val + 'k'
					},
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				}
			},
			legend: {
				show: false,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'left',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#revenue_growth"), options);
		chart.render();
	}

	// Revenue JS
	const getRevenueId = document.getElementById('revenue_ch');
	if (getRevenueId) {
		var options = {
			series: [
				{
					name: "Solved",
					data: [28, 50, 90, 95, 20, 70, 35]
				},
				{
					name: "In Progress",
					data: [80, 60, 70, 30, 45, 20, 80]
				},
				{
					name: "Pending",
					data: [32, 23, 78, 35, 65, 35, 15]
				},
				{
					name: "Others",
					data: [60, 25, 80, 25, 15, 40, 15]
				}
			],
			chart: {
				type: "bar",
				height: 443,
				toolbar: {
					show: true
				}
			},
			colors: [
				"#605DFF", "#3584FC", "#AD63F6", "#FD5812"
			],
			plotOptions: {
				bar: {
					columnWidth: "45%"
				}
			},
			grid: {
				show: true,
				borderColor: "#ECEEF2"
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				width: 3,
				show: true,
				colors: ["transparent"]
			},
			xaxis: {
				categories: [
					"Mon",
					"Tue",
					"Wed",
					"Thu",
					"Fri",
					"Sat",
					"Sun"
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: true,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 5,
				max: 100,
				min: 0,
				labels: {
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				}
			},
			tooltip: {
				y: {
					formatter: function(val) {
						return val + " Tickets";
					}
				}
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'left',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#revenue_ch"), options);
		chart.render();
	}

    /* Lead Conversion JS*/
	const getLeadConversionId = document.getElementById('lead_conversion');
	if (getLeadConversionId) {
		var options = { 
			series: [
				{
					name: "Lead Conversion",
					data: [3, 6, 7, 6, 9, 10, 7]
				}
			],
			chart: {
				type: "area",
				height: 95,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			colors: [
				"#605DFF"
			],
			dataLabels: {
				enabled: false
			},
			grid: {
				show: false,
				borderColor: "#ECEEF2"
			},
			stroke: {
				curve: "smooth",
				width: 1
			},
			xaxis: {
				categories: [
					"Mon",
					"Tue",
					"Wed",
					"Thu",
					"Fri",
					"Sat",
					"Sun"
				],
				axisTicks: {
					show: false,
					color: '#B1BBC8'
				},
				axisBorder: {
					show: false,
					color: '#B1BBC8'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				// tickAmount: 6,
				show: false,
				// max: 150,
				// min: 0,
				labels: {
					formatter: (val) => {
						return val + '%'
					},
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				}
			},
			legend: {
				show: false,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'left',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#lead_conversion"), options);
		chart.render();
	}
    

    /* Total Orders JS*/ 
	const getTotalOrder2Id = document.getElementById('total_orders2');
	if (getTotalOrder2Id) {
		var options = { 
			series: [
				{
					name: "Total Orders",
					data: [44, 55, 57, 56, 61, 58, 63]
				}
			],
			chart: {
				type: "bar",
				height: 110,
				toolbar: {
					show: false
				}
			},
			plotOptions: {
				bar: {
					columnWidth: "50%",
					borderRadius: 2
				}
			},
			colors: [
				"#0dcaf0"
			],
			dataLabels: {
				enabled: false
			},
			grid: {
				show: false,
				borderColor: "#ECEEF2"
			},
			stroke: {
				width: 2,
				show: true,
				colors: ["transparent"]
			},
			xaxis: {
				categories: [
					"Mon",
					"Tue",
					"Wed",
					"Thu",
					"Fri",
					"Sat",
					"Sun"
				],
				axisTicks: {
					show: false,
					color: '#B1BBC8'
				},
				axisBorder: {
					show: false,
					color: '#B1BBC8'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				// tickAmount: 6,
				show: false,
				// max: 150,
				// min: 0,
				labels: {
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				}
			},
			tooltip: {
				y: {
					formatter: function(val) {
						return "$" + val;
					}
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#total_orders2"), options);
		chart.render();
	}
    

    /* Annual Profit JS*/
	const getAnnualProfitId = document.getElementById('annual_profit');
	if (getAnnualProfitId) {
		var options = { 
			series: [
				{
					name: "Annual Profit",
					data: [3, 12, 8, 10, 15, 10, 7]
				}
			],
			chart: {
				type: "area",
				height: 95,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			colors: [
				"#C52B09"
			],
			dataLabels: {
				enabled: false
			},
			grid: {
				show: false,
				borderColor: "#ECEEF2"
			},
			stroke: {
				curve: "smooth",
				width: 1
			},
			xaxis: {
				categories: [
					"Mon",
					"Tue",
					"Wed",
					"Thu",
					"Fri",
					"Sat",
					"Sun"
				],
				axisTicks: {
					show: false,
					color: '#B1BBC8'
				},
				axisBorder: {
					show: false,
					color: '#B1BBC8'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				// tickAmount: 6,
				show: false,
				// max: 150,
				// min: 0,
				labels: {
					formatter: (val) => {
						return val + '%'
					},
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				}
			},
			legend: {
				show: false,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'left',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#annual_profit"), options);
		chart.render();
	}
    

    /* Balance Overview JS*/
	const getBalanceOverviewId = document.getElementById('balance_overview');
	if (getBalanceOverviewId) {
		var options = {
			series: [
				{
					name: "Revenue",
					data: [5, 12, 20, 23, 25, 30, 40, 45, 50, 70, 65, 80 ]
				},
				{
					name: "Expenses",
					data: [15, 20, 30, 30, 35, 45, 60, 70, 80, 85, 95, 120 ]
				}
			],
			chart: {
				type: "area",
				height: 355,
				zoom: {
					enabled: false
				}
			},
			colors: [
				"#AD63F6", "#605DFF"
			],
			dataLabels: {
				enabled: false
			},
			grid: {
				borderColor: "#ECEEF2"
			},
			stroke: {
				curve: "straight",
				width: 2
			},
			xaxis: {
				categories: [
					"Jan",
					"Feb",
					"Mar",
					"Apr",
					"May",
					"Jun",
					"Jul",
					"Aug",
					"Sep",
					"Oct",
					"Nov",
					"Dec"
				],
				axisTicks: {
					show: false,
					color: '#B1BBC8'
				},
				axisBorder: {
					show: false,
					color: '#B1BBC8'
				},
				labels: {
					show: true,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 6,
				max: 150,
				min: 0,
				labels: {
					formatter: (val) => {
						return '$' + val + 'k'
					},
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				}
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'left',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#balance_overview"), options);
		chart.render();
	}
    

    /* Leads by Source JS*/
	const getLeadsBySourceId = document.getElementById('leads_by_source');
	if (getLeadsBySourceId) {
		var options = {
			series: [320, 60, 30, 160, 279, 19],
			chart: {
				height: 266,
				type: "donut"
			},
			labels: [
				"Organic", "Paid", "Direct", "Social", "Referrals", "Others"
			],
			colors: [
				"#605DFF", "#3584FC", "#AD63F6", "#0dcaf0", "#25B003", "#FD5812"
			],
			stroke: {
				width: 1,
				show: true,
				colors: ["#ffffff"]
			},
			legend: {
				show: false,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					shape: "diamond",
				}
			},
			plotOptions: {
				pie: {
					expandOnClick: false,
					donut: {
						labels: {
							show: true,
							name: {
								color: '#64748B'
							},
							value: {
								show: true,
								color: '#3A4252',
								fontSize: '28px',
								fontWeight: '600'
							},
							total: {
								show: true,
								color: '#64748B'
							}
						}
					}
				}
			},
			dataLabels: {
				enabled: false
			},
			tooltip: {
				enabled: false
			}
		}
		var chart = new ApexCharts(document.querySelector("#leads_by_source"), options);
		chart.render();
	}
    

    /* Sales Report JS*/
	const getSalesReportId = document.getElementById('sales_report');
	if (getSalesReportId) {
		var options = {
			series: [
				{
					name: "Online",
					data: [45, 23, 62, 60, 110, 100, 135]
				},
				{
					name: "Offline",
					data: [20, 58, 24, 50, 40, 70, 97]
				}
			],
			chart: {
				height: 378,
				type: "line",
				zoom: {
					enabled: false
				},
				toolbar: {
					show: true
				}
			},
			dataLabels: {
				enabled: false
			},
			colors: [
				"#605DFF", "#FE7A36"
			],
			stroke: {
				curve: "straight",
				width: 2
			},
			grid: {
				show: true,
				borderColor: "#F6F7F9"
			},
			markers: {
				size: 4,
				strokeWidth: 0,
				shape: ["circle", "square"],
				hover: {
					size: 5
				}
			},
			xaxis: {
				categories: [
					"Sun",
					"Mon",
					"Tue",
					"Wed",
					"Thu",
					"Fri",
					"Sat"
				],
				axisTicks: {
					show: false,
					color: '#B1BBC8'
				},
				axisBorder: {
					show: false,
					color: '#B1BBC8'
				},
				labels: {
					show: true,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 6,
				max: 150,
				min: 0,
				labels: {
					formatter: (val) => {
						return '$' + val + 'k'
					},
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				}
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'left',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#sales_report"), options);
		chart.render();
	}


	//**<---- Dashboard Project Management Charts ---->**//
    
	/* Projects Roadmap JS */
	const getProjectsRoadmapId = document.getElementById('projects_roadmap');
	if (getProjectsRoadmapId) {
		var options = {

			series: [
				{
					name: "Projects",
					data: [400, 550, 600, 700, 1000, 1100, 1200]
				}
			],
			chart: {
				type: "bar",
				height: 342,
				toolbar: {
					show: false
				}
			},
			colors: [
				"#3584FC"
			],
			plotOptions: {
				bar: {
					horizontal: true
				}
			},
			grid: {
				show: true,
				borderColor: "#ECEEF2"
			},
			dataLabels: {
				enabled: false
			},
			xaxis: {
				categories: [
					"Project Planning",
					"Research",
					"Design",
					"Front-end",
					"Development",
					"Review & QA",
					"Launch"
				],
				axisTicks: {
					show: true,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: true,
					color: '#ECEEF2'
				},
				labels: {
					show: true,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				labels: {
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				},
				axisBorder: {
					show: true,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: true,
					color: '#ECEEF2'
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#projects_roadmap"), options);
		chart.render();
	}

	/* Projects Roadmap 2 JS */
	const getProjectsRoadmap2Id = document.getElementById('projects_roadmap2');
	if (getProjectsRoadmap2Id) {
		var options = {

			series: [
				{
					name: "Projects",
					data: [400, 550, 600, 700, 1000, 1100, 1200]
				}
			],
			chart: {
				type: "bar",
				height: 260,
				toolbar: {
					show: false
				}
			},
			colors: [
				"#3584FC"
			],
			plotOptions: {
				bar: {
					horizontal: true
				}
			},
			grid: {
				show: true,
				borderColor: "#ECEEF2"
			},
			dataLabels: {
				enabled: false
			},
			xaxis: {
				categories: [
					"Project Planning",
					"Research",
					"Design",
					"Front-end",
					"Development",
					"Review & QA",
					"Launch"
				],
				axisTicks: {
					show: true,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: true,
					color: '#ECEEF2'
				},
				labels: {
					show: true,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				labels: {
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				},
				axisBorder: {
					show: true,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: true,
					color: '#ECEEF2'
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#projects_roadmap2"), options);
		chart.render();
	}
    
	/* Projects Progress JS */
	const getProjectsProgressId = document.getElementById('projects_progress');
	if (getProjectsProgressId) {
		var options = {

			series: [
				{
					name: "Completed",
					data: [70, 23, 45, 30, 62, 70]
				},
				{
					name: "In Progress",
					data: [15, 40, 37, 38, 80, 45]
				},
				{
					name: "Not Start Yet",
					data: [50, 11, 60, 15, 31, 30]
				},
				{
					name: "Cancelled",
					data: [30, 60, 25, 22, 50, 15]
				}
			],
			chart: {
				height: 366,
				type: "line",
				zoom: {
					enabled: false
				},
				toolbar: {
					show: true
				}
			},
			dataLabels: {
				enabled: false
			},
			colors: [
				"#605DFF", "#FE7A36", "#AD63F6", "#D71C00"
			],
			stroke: {
				curve: "smooth",
				width: 2
			},
			grid: {
				show: true,
				borderColor: "#ECEEF2"
			},
			markers: {
				size: 4,
				strokeWidth: 0,
				shape: ["circle", "square", "circle", "square"],
				hover: {
					size: 5
				}
			},
			xaxis: {
				categories: [
					"Jan",
					"Feb",
					"Mar",
					"Apr",
					"May",
					"Jun"
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: true,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 5,
				max: 100,
				min: 0,
				labels: {
					formatter: (val) => {
						return val + '%'
					},
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				}
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'left',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					shape: "diamond",
				}
			}
			
		};
		var chart = new ApexCharts(document.querySelector("#projects_progress"), options);
		chart.render();
	}
    
	/* Project Analysis JS */
	const getProjectAnalysisId = document.getElementById('project_analysis');
	if (getProjectAnalysisId) {
		var options = {

			series: [
				{
					name: "Budgets",
					data: [40, 60, 55, 30, 60, 130, 63,]
				},
				{
					name: "Expenses",
					data: [15, 65, 100, 40, 90, 90, 91]
				},
				{
					name: "Revenue",
					data: [55, 70, 30, 50, 110, 60, 52]
				}
			],
			chart: {
				type: "bar",
				height: 370,
				toolbar: {
					show: true
				}
			},
			colors: [
				"#605DFF", "#AD63F6", "#3584FC"
			],
			plotOptions: {
				bar: {
					columnWidth: "60%"
				}
			},
			grid: {
				show: true,
				borderColor: "#ECEEF2"
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				width: 4,
				show: true,
				colors: ["transparent"]
			},
			xaxis: {
				categories: [
					"Mon",
					"Tue",
					"Wed",
					"Thu",
					"Fri",
					"Sat",
					"Sun"
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: true,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 6,
				max: 150,
				min: 0,
				labels: {
					// formatter: (val) => {
					//     return '$' + val + 'k'
					// },
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				}
			},
			tooltip: {
				y: {
					formatter: function(val) {
						return "$" + val + "k";
					}
				}
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'left',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			}
			
		};
		var chart = new ApexCharts(document.querySelector("#project_analysis"), options);
		chart.render();
	}

	/* Project Analysis JS */
	const getProjectAnalysis2Id = document.getElementById('project_analysis2');
	if (getProjectAnalysis2Id) {
		var options = {

			series: [
				{
					name: "Budgets",
					data: [40, 60, 55, 30, 60, 130, 63,]
				},
				{
					name: "Expenses",
					data: [15, 65, 100, 40, 90, 90, 91]
				},
				{
					name: "Revenue",
					data: [55, 70, 30, 50, 110, 60, 52]
				}
			],
			chart: {
				type: "bar",
				height: 400,
				toolbar: {
					show: true
				}
			},
			colors: [
				"#605DFF", "#AD63F6", "#3584FC"
			],
			plotOptions: {
				bar: {
					columnWidth: "25%"
				}
			},
			grid: {
				show: true,
				borderColor: "#ECEEF2"
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				width: 4,
				show: true,
				colors: ["transparent"]
			},
			xaxis: {
				categories: [
					"Mon",
					"Tue",
					"Wed",
					"Thu",
					"Fri",
					"Sat",
					"Sun"
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: true,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 6,
				max: 150,
				min: 0,
				labels: {
					// formatter: (val) => {
					//     return '$' + val + 'k'
					// },
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				}
			},
			tooltip: {
				y: {
					formatter: function(val) {
						return "$" + val + "k";
					}
				}
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'left',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			}
			
		};
		var chart = new ApexCharts(document.querySelector("#project_analysis2"), options);
		chart.render();
	}
    
	/* Tasks Overview JS */
	const getTasksOverviewId = document.getElementById('tasks_overview');
	if (getTasksOverviewId) {
		var options = {

			series: [
				55, 44, 30, 12
			],
			chart: {
				height: 382,
				type: "pie",
			},
			labels: [
				"Solved", "In Progress", "Pending", "Unassigned"
			],
			colors: [
				"#37D80A", "#605DFF", "#AD63F6", "#FD5812"
			],
			dataLabels: {
				enabled: false
			},
			plotOptions: {
				pie: {
					expandOnClick: false
				}
			},
			stroke: {
				width: 1,
				show: true,
				colors: ["#ffffff"]
			},
			legend: {
				show: true,
				fontSize: '12px',
				position: 'bottom',
				horizontalAlign: 'center',
				itemMargin: {
					horizontal: 8,
					vertical: 7
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					radius: 2,
					height: 9,
					offsetX: -2,
					offsetY: -.1,
					shape: "diamond",
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#tasks_overview"), options);
		chart.render();
	}

	/* Tasks Overview JS */
	const getTasksOverview4Id = document.getElementById('tasks_overview4');
	if (getTasksOverview4Id) {
		var options = {

			series: [
				55, 44, 30, 12, 22
			],
			chart: {
				height: 381,
				type: "pie",
			},
			labels: [
				"Completed", "In Progress", "Pending", "Active", "Cancelled"
			],
			colors: [
				"#37D80A", "#605DFF", "#AD63F6", "#3584FC", "#FD5812"
			],
			dataLabels: {
				enabled: false
			},
			plotOptions: {
				pie: {
					expandOnClick: false
				}
			},
			stroke: {
				width: 1,
				show: true,
				colors: ["#ffffff"]
			},
			legend: {
				show: true,
				fontSize: '12px',
				position: 'bottom',
				horizontalAlign: 'center',
				itemMargin: {
					horizontal: 8,
					vertical: 7
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					radius: 2,
					height: 9,
					offsetX: -2,
					offsetY: -.1,
					shape: "diamond",
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#tasks_overview4"), options);
		chart.render();
	}

	/* Tasks Overview JS */
	const getTasksOverview3Id = document.getElementById('tasks_overview3');
	if (getTasksOverview3Id) {
		var options = {

			series: [
				55, 44, 30, 12, 22
			],
			chart: {
				height: 140,
				type: "pie",
			},
			labels: [
				"Completed", "In Progress", "Pending", "Active", "Cancelled"
			],
			colors: [
				"#37D80A", "#605DFF", "#AD63F6", "#3584FC", "#FD5812"
			],
			dataLabels: {
				enabled: false
			},
			plotOptions: {
				pie: {
					expandOnClick: false
				}
			},
			stroke: {
				width: 1,
				show: true,
				colors: ["#ffffff"]
			},
			legend: {
				show: true,
				fontSize: '12px',
				position: 'bottom',
				horizontalAlign: 'center',
				itemMargin: {
					horizontal: 8,
					vertical: 7
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					radius: 2,
					height: 9,
					offsetX: -2,
					offsetY: -.1,
					shape: "diamond",
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#tasks_overview3"), options);
		chart.render();
	}
    

	//**<---- Dashboard LMS Charts ---->**//

	// Student’s Interested Topics JS
	const getStudentsInterestedTopicsId = document.getElementById('student_interested_topics');
	if (getStudentsInterestedTopicsId) {
		var options = {
			series: [
				{
					name: "Courses",
					data: [47, 69, 37, 17, 28, 40]
				}
			],
			chart: {
				type: "bar",
				height: 396,
				toolbar: {
					show: false
				}
			},
			colors: [
				"#605DFF"
			],
			plotOptions: {
				bar: {
					barHeight: '21px',
					horizontal: true
				}
			},
			grid: {
				show: true,
				borderColor: "#ECEEF2"
			},
			dataLabels: {
				enabled: false
			},
			xaxis: {
				categories: [
					"UX/UI Design",
					"WordPress",
					"Motion Design",
					"Video Editing",
					"Angular",
					"Python"
				],
				axisTicks: {
					show: true,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: true,
					color: '#ECEEF2'
				},
				labels: {
					show: true,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				labels: {
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				},
				axisBorder: {
					show: true,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: true,
					color: '#ECEEF2'
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#student_interested_topics"), options);
		chart.render();
	}
    
    // Courses Sales JS
	const getCoursesSalesId = document.getElementById('courses_sales');
	if (getCoursesSalesId) {
		var options = {
			series: [
				{
					name: "Sales",
					data: [100, 130, 115, 170, 110, 120, 85, 140, 150, 100, 110, 90, 160, 125, 105, 130, 145, 120, 150, 155, 220, 165]
				}
			],
			chart: {
				type: "area",
				height: 270,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: "straight",
				width: 2
			},
			colors: [
				"#605DFF"
			],
			fill: {
				type: 'gradient',
				gradient: {
					stops: [0, 90, 100],
					shadeIntensity: 1,
					opacityFrom: 0.5,
					opacityTo: 0.9
				}
			},
			xaxis: {
				categories: [
					"01 Jan",
					"02 Jan",
					"03 Jan",
					"04 Jan",
					"05 Jan",
					"06 Jan",
					"07 Jan",
					"08 Jan",
					"09 Jan",
					"10 Jan",
					"11 Jan",
					"12 Jan",
					"13 Jan",
					"14 Jan",
					"15 Jan",
					"16 Jan",
					"17 Jan",
					"18 Jan",
					"19 Jan",
					"20 Jan",
					"21 Jan",
					"22 Jan",
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 5,
				show: false,
				max: 250,
				min: 0,
				labels: {
					show: true,
					style: {
						colors: "#64748B",
						fontSize: "12px"
					},
					formatter: (val) => {
						return '$' + val + 'K'
					}
				},
				axisBorder: {
					show: true,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: true,
					color: '#ECEEF2'
				}
			},
			grid: {
				show: false,
				borderColor: "#ECEEF2"
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#courses_sales"), options);
		chart.render();
	}
    
    // Time Spent JS
	const getTimeSpentId = document.getElementById('time_spent');
	if (getTimeSpentId) {
		var options = {
			series: [
				{
					name: "Time Spent",
					data: [30, 70, 80, 95, 90, 20, 40]
				}
			],
			chart: {
				type: "bar",
				height: 255,
				toolbar: {
					show: false
				}
			},
			colors: [
				"#AD63F6"
			],
			plotOptions: {
				bar: {
					columnWidth: "30%"
				}
			},
			grid: {
				show: true,
				borderColor: "#ECEEF2"
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				width: 4,
				show: true,
				colors: ["transparent"]
			},
			xaxis: {
				categories: [
					"Mon",
					"Tue",
					"Wed",
					"Thu",
					"Fri",
					"Sat",
					"Sun"
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: true,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 4,
				max: 100,
				min: 0,
				labels: {
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				}
			},
			tooltip: {
				y: {
					formatter: function(val) {
						return val + " hours";
					}
				}
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'left',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#time_spent"), options);
		chart.render();
	}


	//**<---- Dashboard HelpDesk Charts ---->**//

	// Tickets Resolved JS
	const getTicketsResolvedId = document.getElementById('tickets_resolved');
	if (getTicketsResolvedId) {
		var options = {
			series: [
				{
					name: "Tickets Resolved",
					data: [35, 70, 35, 65, 45, 98, 80]
				}
			],
			chart: {
				type: "area",
				height: 130,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: "straight",
				width: 2
			},
			colors: [
				"#605DFF"
			],
			fill: {
				type: 'gradient',
				gradient: {
					stops: [0, 90, 100],
					shadeIntensity: 1,
					opacityFrom: 0.5,
					opacityTo: 0.9
				}
			},
			xaxis: {
				categories: [
					"Mon",
					"Tue",
					"Wed",
					"Thu",
					"Fri",
					"Sat",
					"Sun"
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 5,
				show: false,
				max: 100,
				min: 0,
				labels: {
					show: true,
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				},
				axisBorder: {
					show: true,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: true,
					color: '#ECEEF2'
				}
			},
			grid: {
				show: false,
				borderColor: "#ECEEF2"
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#tickets_resolved"), options);
		chart.render();
	}

	// In Progress JS
	const getInProgressId = document.getElementById('in_progress');
	if (getInProgressId) {
		var options = {
			series: [
				{
					name: "Tickets In Progress",
					data: [30, 65, 50, 85, 65, 75, 60]
				}
			],
			chart: {
				type: "area",
				height: 130,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: "straight",
				width: 2
			},
			colors: [
				"#FD5812"
			],
			fill: {
				type: 'gradient',
				gradient: {
					stops: [0, 90, 100],
					shadeIntensity: 1,
					opacityFrom: 0.5,
					opacityTo: 0.9
				}
			},
			xaxis: {
				categories: [
					"Mon",
					"Tue",
					"Wed",
					"Thu",
					"Fri",
					"Sat",
					"Sun"
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 5,
				show: false,
				max: 100,
				min: 0,
				labels: {
					show: true,
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				},
				axisBorder: {
					show: true,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: true,
					color: '#ECEEF2'
				}
			},
			grid: {
				show: false,
				borderColor: "#ECEEF2"
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#in_progress"), options);
		chart.render();
	}

	// Tickets Due JS
	const getTicketsDueId = document.getElementById('tickets_due');
	if (getTicketsDueId) {
		var options = {
			series: [
				{
					name: "Tickets Due",
					data: [35, 70, 40, 65, 45, 70, 65]
				}
			],
			chart: {
				type: "area",
				height: 130,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: "straight",
				width: 2
			},
			colors: [
				"#AD63F6"
			],
			fill: {
				type: 'gradient',
				gradient: {
					stops: [0, 90, 100],
					shadeIntensity: 1,
					opacityFrom: 0.5,
					opacityTo: 0.9
				}
			},
			xaxis: {
				categories: [
					"Mon",
					"Tue",
					"Wed",
					"Thu",
					"Fri",
					"Sat",
					"Sun"
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 5,
				show: false,
				max: 100,
				min: 0,
				labels: {
					show: true,
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				},
				axisBorder: {
					show: true,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: true,
					color: '#ECEEF2'
				}
			},
			grid: {
				show: false,
				borderColor: "#ECEEF2"
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#tickets_due"), options);
		chart.render();
	}

	// Tickets New Open JS
	const getTicketsNewOpenId = document.getElementById('tickets_new_open');
	if (getTicketsNewOpenId) {
		var options = {
			series: [
				{
					name: "Tickets New Open",
					data: [40, 55, 35, 85, 50, 85, 95]
				}
			],
			chart: {
				type: "area",
				height: 130,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: "straight",
				width: 2
			},
			colors: [
				"#37D80A"
			],
			fill: {
				type: 'gradient',
				gradient: {
					stops: [0, 90, 100],
					shadeIntensity: 1,
					opacityFrom: 0.5,
					opacityTo: 0.9
				}
			},
			xaxis: {
				categories: [
					"Mon",
					"Tue",
					"Wed",
					"Thu",
					"Fri",
					"Sat",
					"Sun"
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 5,
				show: false,
				max: 100,
				min: 0,
				labels: {
					show: true,
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				},
				axisBorder: {
					show: true,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: true,
					color: '#ECEEF2'
				}
			},
			grid: {
				show: false,
				borderColor: "#ECEEF2"
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#tickets_new_open"), options);
		chart.render();
	}

    // Tickets Status JS
	const getTicketsStatusId = document.getElementById('tickets_status');
	if (getTicketsStatusId) {
		var options = {
			series: [
				{
					name: "Solved",
					data: [28, 50, 90, 95, 20, 70, 35]
				},
				{
					name: "In Progress",
					data: [80, 60, 70, 30, 45, 20, 80]
				},
				{
					name: "Pending",
					data: [32, 23, 78, 35, 65, 35, 15]
				},
				{
					name: "Others",
					data: [60, 25, 80, 25, 15, 40, 15]
				}
			],
			chart: {
				type: "bar",
				height: 395,
				toolbar: {
					show: false
				}
			},
			colors: [
				"#605DFF", "#3584FC", "#AD63F6", "#FD5812"
			],
			plotOptions: {
				bar: {
					columnWidth: "65%"
				}
			},
			grid: {
				show: true,
				borderColor: "#ECEEF2"
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				width: 3,
				show: true,
				colors: ["transparent"]
			},
			xaxis: {
				categories: [
					"Mon",
					"Tue",
					"Wed",
					"Thu",
					"Fri",
					"Sat",
					"Sun"
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: true,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 5,
				max: 100,
				min: 0,
				labels: {
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				}
			},
			tooltip: {
				y: {
					formatter: function(val) {
						return val + " Tickets";
					}
				}
			},
			legend: {
				show: false,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'left',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#tickets_status"), options);
		chart.render();
	}
    
    // Customer Satisfaction JS
	const getCustomerSatisfactionId = document.getElementById('customer_satisfaction');
	if (getCustomerSatisfactionId) {
		var options = {
			series: [50, 15, 75, 50],
			chart: {
				height: 147,
				type: "donut"
			},
			labels: [
				"Highly Satisfied", "Satisfied", "Low Satisfied", "Unsatisfied"
			],
			colors: [
				"#AD63F6", "#C2CDFF", "#FFAA72", "#0dcaf0"
			],
			stroke: {
				width: 1,
				show: true,
				colors: ["transparent"]
			},
			legend: {
				show: false,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					shape: "diamond",
				}
			},
			plotOptions: {
				pie: {
					expandOnClick: false,
					donut: {
						size: '73%',
						labels: {
							show: true,
							name: {
								color: '#fff'
							},
							value: {
								show: true,
								color: '#fff',
								fontSize: '28px',
								fontWeight: '600',
							},
							total: {
								show: true,
								color: '#fff',
								label: 'Overall',
							}
						}
					}
				}
			},
			dataLabels: {
				enabled: false
			},
			tooltip: {
				enabled: true,
				y: {
					formatter: function(val) {
						return val + "%";
					}
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#customer_satisfaction"), options);
		chart.render();
	}

	// Customer Satisfaction JS
	const getCustomerSatisfaction2Id = document.getElementById('customer_satisfaction2');
	if (getCustomerSatisfaction2Id) {
		var options = {
			series: [50, 15, 75, 50],
			chart: {
				height: 152,
				type: "donut"
			},
			labels: [
				"Highly Satisfied", "Satisfied", "Low Satisfied", "Unsatisfied"
			],
			colors: [
				"#AD63F6", "#C2CDFF", "#FFAA72", "#0dcaf0"
			],
			stroke: {
				width: 1,
				show: true,
				colors: ["transparent"]
			},
			legend: {
				show: false,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					shape: "diamond",
				}
			},
			plotOptions: {
				pie: {
					expandOnClick: false,
					donut: {
						size: '73%',
						labels: {
							show: false,
							name: {
								color: '#64748B'
							},
							value: {
								show: true,
								color: '#3A4252',
								fontSize: '28px',
								fontWeight: '600'
							},
							total: {
								show: true,
								color: '#64748B'
							}
						}
					}
				}
			},
			dataLabels: {
				enabled: false
			},
			tooltip: {
				enabled: true,
				y: {
					formatter: function(val) {
						return val + "%";
					}
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#customer_satisfaction2"), options);
		chart.render();
	}
    
    // Response Time JS
	const getResponseTimeId = document.getElementById('response_time');
	if (getResponseTimeId) {
		var options = {
			series: [
				{
					name: "Response Time",
					data: [100, 130, 115, 170, 110, 120, 85, 140, 150, 100, 110, 90, 160, 125, 105, 130, 145, 120, 150, 155, 220, 165]
				}
			],
			chart: {
				type: "area",
				height: 205,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: "straight",
				width: 2
			},
			colors: [
				"#FFB264"
			],
			fill: {
				type: 'gradient',
				gradient: {
					stops: [0, 90, 100],
					shadeIntensity: 1,
					opacityFrom: 0.5,
					opacityTo: 0.9
				}
			},
			xaxis: {
				categories: [
					"01 Jan",
					"02 Jan",
					"03 Jan",
					"04 Jan",
					"05 Jan",
					"06 Jan",
					"07 Jan",
					"08 Jan",
					"09 Jan",
					"10 Jan",
					"11 Jan",
					"12 Jan",
					"13 Jan",
					"14 Jan",
					"15 Jan",
					"16 Jan",
					"17 Jan",
					"18 Jan",
					"19 Jan",
					"20 Jan",
					"21 Jan",
					"22 Jan",
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 5,
				show: false,
				max: 250,
				min: 0,
				labels: {
					show: true,
					style: {
						colors: "#64748B",
						fontSize: "12px"
					},
					formatter: (val) => {
						return val + ' mins'
					}
				},
				axisBorder: {
					show: true,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: true,
					color: '#ECEEF2'
				}
			},
			grid: {
				show: false,
				borderColor: "#ECEEF2"
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#response_time"), options);
		chart.render();
	}

	// Response Time JS
	const getResponseTime2Id = document.getElementById('response_time2');
	if (getResponseTime2Id) {
		var options = {
			series: [
				{
					name: "Response Time",
					data: [100, 130, 115, 170, 110, 120, 85, 140, 150, 100, 110, 90, 160, 125, 105, 130, 145, 120, 150, 155, 220, 165]
				}
			],
			chart: {
				type: "area",
				height: 212,
				width: 600,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: "straight",
				width: 2
			},
			colors: [
				"#FFB264"
			],
			fill: {
				type: 'gradient',
				gradient: {
					stops: [0, 90, 100],
					shadeIntensity: 1,
					opacityFrom: 0.5,
					opacityTo: 0.9
				}
			},
			xaxis: {
				categories: [
					"01 Jan",
					"02 Jan",
					"03 Jan",
					"04 Jan",
					"05 Jan",
					"06 Jan",
					"07 Jan",
					"08 Jan",
					"09 Jan",
					"10 Jan",
					"11 Jan",
					"12 Jan",
					"13 Jan",
					"14 Jan",
					"15 Jan",
					"16 Jan",
					"17 Jan",
					"18 Jan",
					"19 Jan",
					"20 Jan",
					"21 Jan",
					"22 Jan",
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 5,
				show: false,
				max: 250,
				min: 0,
				labels: {
					show: true,
					style: {
						colors: "#64748B",
						fontSize: "12px"
					},
					formatter: (val) => {
						return val + ' mins'
					}
				},
				axisBorder: {
					show: true,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: true,
					color: '#ECEEF2'
				}
			},
			grid: {
				show: false,
				borderColor: "#ECEEF2"
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#response_time2"), options);
		chart.render();
	}
	
    // New Tickets vs Solved Tickets JS
	const getNewTicketsVsSolvedTicketsId = document.getElementById('new_tickets_vs_solved_tickets');
	if (getNewTicketsVsSolvedTicketsId) {
		var options = {
			series: [
				{
					name: "New Tickets",
					data: [25, 70, 25, 45, 60, 55, 70]
				},
				{
					name: "Solved Tickets",
					data: [65, 45, 65, 30, 75, 24, 50]
				}
			],
			chart: {
				type: "area",
				height: 492,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			colors: [
				"#605DFF", "#FD5812"
			],
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: "smooth",
				width: 2
			},
			grid: {
				show: true,
				borderColor: "#ECEEF2"
			},
			fill: {
				type: 'gradient',
				gradient: {
					stops: [0, 90, 100],
					shadeIntensity: 1,
					opacityFrom: 0,
					opacityTo: 0.6
				}
			},
			xaxis: {
				categories: [
					"Mon",
					"Tue",
					"Wed",
					"Thu",
					"Fri",
					"Sat",
					"Sun"
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: true,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 4,
				max: 80,
				min: 0,
				labels: {
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				}
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#new_tickets_vs_solved_tickets"), options);
		chart.render();
	}
    
    /* Tasks Overview JS */
	const getTasksOverview2Id = document.getElementById('tasks_overview2');
	if (getTasksOverview2Id) {
		var options = {

			series: [
				55, 44, 30, 12, 22
			],
			chart: {
				height: 460,
				type: "pie",
			},
			labels: [
				"Completed", "In Progress", "Pending", "Active", "Cancelled"
			],
			colors: [
				"#37D80A", "#605DFF", "#AD63F6", "#3584FC", "#FD5812"
			],
			dataLabels: {
				enabled: false
			},
			plotOptions: {
				pie: {
					expandOnClick: false
				}
			},
			stroke: {
				width: 1,
				show: true,
				colors: ["#ffffff"]
			},
			legend: {
				show: true,
				fontSize: '12px',
				position: 'bottom',
				horizontalAlign: 'center',
				itemMargin: {
					horizontal: 8,
					vertical: 7
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					radius: 2,
					height: 9,
					offsetX: -2,
					offsetY: -.1,
					shape: "diamond",
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#tasks_overview2"), options);
		chart.render();
	}

	// New vs Solved JS
	const getNewVsSolvedId = document.getElementById('new_vs_solved');
	if (getNewVsSolvedId) {
		var options = {
			series: [
				{
					name: "New Tickets",
					data: [25, 70, 25, 45, 60, 55, 70]
				},
				{
					name: "Solved Tickets",
					data: [65, 45, 65, 30, 75, 24, 50]
				}
			],
			chart: {
				type: "area",
				height: 395,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			colors: [
				"#605DFF", "#FD5812"
			],
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: "smooth",
				width: 2
			},
			grid: {
				show: true,
				borderColor: "#ECEEF2"
			},
			fill: {
				type: 'gradient',
				gradient: {
					stops: [0, 90, 100],
					shadeIntensity: 1,
					opacityFrom: 0,
					opacityTo: 0.6
				}
			},
			xaxis: {
				categories: [
					"Mon",
					"Tue",
					"Wed",
					"Thu",
					"Fri",
					"Sat",
					"Sun"
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: true,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 4,
				max: 80,
				min: 0,
				labels: {
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				}
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#new_vs_solved"), options);
		chart.render();
	}


	//**<---- Dashboard Analytics Charts ---->**//


	// Analytics Overview JS
	const getAnalyticsOverviewId = document.getElementById('analytics_overview');
	if (getAnalyticsOverviewId) {
		var options = {
			series: [
				{
					name: "New users",
					data: [28, 50, 90, 95, 20, 70, 35]
				},
				{
					name: "Page Views",
					data: [80, 60, 70, 30, 45, 20, 80]
				},
				{
					name: "Page Sessions",
					data: [32, 23, 78, 35, 65, 35, 15]
				},
				{
					name: "Bounce Rate",
					data: [60, 25, 80, 25, 15, 40, 15]
				}
			],
			chart: {
				type: "bar",
				height: 374,
				toolbar: {
					show: false
				}
			},
			colors: [
				"#605DFF", "#AD63F6", "#3584FC", "#FD5812"
			],
			plotOptions: {
				bar: {
					columnWidth: "65%",
					borderRadius: 4
				}
			},
			grid: {
				show: true,
				borderColor: "#ECEEF2"
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				width: 3,
				show: true,
				colors: ["transparent"]
			},
			xaxis: {
				categories: [
					"Mon",
					"Tue",
					"Wed",
					"Thu",
					"Fri",
					"Sat",
					"Sun"
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: true,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 5,
				max: 100,
				min: 0,
				labels: {
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				}
			},
			legend: {
				show: false,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'left',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					size: 7,
					offsetX: -2,
					offsetY: -.5,
					shape: 'diamond'
				}
			},
		};
		var chart = new ApexCharts(document.querySelector("#analytics_overview"), options);
		chart.render();
	}

	// Realtime JS
	const getRealtimeId = document.getElementById('realtime');
	if (getRealtimeId) {
		var options = {
			series: [
				{
					name: "Users",
					data: [100, 90, 85, 95, 100, 100, 90, 85, 95, 100, 100, 90, 85, 95, 100, 100, 90, 85, 95, 100, 100, 90, 85, 95, 100, 100, 90, 85, 95, 100]
				}
			],
			chart: {
				type: "bar",
				height: 170,
				toolbar: {
					show: false
				}
			},
			colors: [
				"#757DFF"
			],
			plotOptions: {
				bar: {
					columnWidth: "100%",
				}
			},
			grid: {
				show: false,
				borderColor: "#ECEEF2"
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				width: 3,
				show: true,
				colors: ["transparent"]
			},
			xaxis: {
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 5,
				max: 100,
				min: 0,
				labels: {
					show: false,
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				}
			},
			legend: {
				show: false,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'left',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					size: 7,
					offsetX: -2,
					offsetY: -.5,
					shape: 'diamond'
				}
			},
		};
		var chart = new ApexCharts(document.querySelector("#realtime"), options);
		chart.render();
	}

	/* Device Sessions JS */
	const getDeviceSessionsId = document.getElementById('device_sessions');
	if (getDeviceSessionsId) {
		var options = {

			series: [
				55, 44, 30, 12
			],
			chart: {
				height: 280,
				type: "pie",
			},
			labels: [
				"Desktop", "Mobile", "Tablet", "Other"
			],
			colors: [
				"#37D80A", "#605DFF", "#AD63F6", "#FD5812"
			],
			dataLabels: {
				enabled: false
			},
			plotOptions: {
				pie: {
					expandOnClick: false
				}
			},
			stroke: {
				width: 1,
				show: true,
				colors: ["#ffffff"]
			},
			legend: {
				show: false,
				fontSize: '12px',
				position: 'bottom',
				horizontalAlign: 'center',
				itemMargin: {
					horizontal: 8,
					vertical: 7
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					radius: 2,
					height: 9,
					offsetX: -2,
					offsetY: -.1,
					shape: "diamond",
				}
			},
			tooltip: {
				enabled: true,
				y: {
					formatter: function(val) {
						return val + "%";
					}
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#device_sessions"), options);
		chart.render();
	}

	/* Website Visits JS */
	const getWebsiteVisitsId = document.getElementById('website_visits');
	if (getWebsiteVisitsId) {
		var options = {
			series: [
				{
					name: "Website Visits",
					data: [3, 7, 7, 10, 9, 11, 15]
				}
			],
			chart: {
				type: "area",
				height: 95,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			colors: [
				"#3584FC"
			],
			dataLabels: {
				enabled: false
			},
			grid: {
				show: false,
				borderColor: "#ECEEF2"
			},
			stroke: {
				curve: "straight",
				width: 1
			},
			xaxis: {
				categories: [
					"Mon",
					"Tue",
					"Wed",
					"Thu",
					"Fri",
					"Sat",
					"Sun"
				],
				axisTicks: {
					show: false,
					color: '#B1BBC8'
				},
				axisBorder: {
					show: false,
					color: '#B1BBC8'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				// tickAmount: 6,
				show: false,
				// max: 150,
				// min: 0,
				labels: {
					formatter: (val) => {
						return '' + val + 'k'
					},
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				}
			},
			legend: {
				show: false,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'left',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#website_visits"), options);
		chart.render();
	}

	/* New Registers JS*/
	const getNewRegistersId = document.getElementById('new_registers');
	if (getNewRegistersId) {
		var options = { 
			series: [
				{
					name: "New Registers",
					data: [3, 12, 8, 10, 15, 10, 7]
				}
			],
			chart: {
				type: "area",
				height: 95,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			colors: [
				"#C52B09"
			],
			dataLabels: {
				enabled: false
			},
			grid: {
				show: false,
				borderColor: "#ECEEF2"
			},
			stroke: {
				curve: "smooth",
				width: 1
			},
			xaxis: {
				categories: [
					"Mon",
					"Tue",
					"Wed",
					"Thu",
					"Fri",
					"Sat",
					"Sun"
				],
				axisTicks: {
					show: false,
					color: '#B1BBC8'
				},
				axisBorder: {
					show: false,
					color: '#B1BBC8'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				// tickAmount: 6,
				show: false,
				// max: 150,
				// min: 0,
				labels: {
					formatter: (val) => {
						return val + 'k'
					},
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				}
			},
			legend: {
				show: false,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'left',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#new_registers"), options);
		chart.render();
	}

	/* Clicks JS*/
	const getClicksId = document.getElementById('clicks');
	if (getClicksId) {
		var options = { 
			series: [
				{
					name: "Clicks",
					data: [100, 130, 115, 170, 110, 120, 85, 140, 150, 100, 110, 90, 160, 125, 105, 130, 145, 120, 150, 155, 220, 165]
				}
			],
			chart: {
				type: "area",
				height: 150,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: "straight",
				width: 2
			},
			colors: [
				"#605DFF"
			],
			fill: {
				type: 'gradient',
				gradient: {
					stops: [0, 90, 100],
					shadeIntensity: 1,
					opacityFrom: 0.5,
					opacityTo: 0.9
				}
			},
			xaxis: {
				categories: [
					"01 Jan",
					"02 Jan",
					"03 Jan",
					"04 Jan",
					"05 Jan",
					"06 Jan",
					"07 Jan",
					"08 Jan",
					"09 Jan",
					"10 Jan",
					"11 Jan",
					"12 Jan",
					"13 Jan",
					"14 Jan",
					"15 Jan",
					"16 Jan",
					"17 Jan",
					"18 Jan",
					"19 Jan",
					"20 Jan",
					"21 Jan",
					"22 Jan",
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 5,
				show: false,
				max: 250,
				min: 0,
				labels: {
					show: true,
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				},
				axisBorder: {
					show: true,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: true,
					color: '#ECEEF2'
				}
			},
			grid: {
				show: false,
				borderColor: "#ECEEF2"
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					size: 7,
					offsetX: -2,
					offsetY: -.5,
					shape: 'diamond',
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#clicks"), options);
		chart.render();
	}

	/* Impressions JS*/
	const getImpressionsId = document.getElementById('impressions');
	if (getImpressionsId) {
		var options = { 
			series: [
				{
					name: "Impressions",
					data: [100, 110, 90, 160, 125, 105, 130, 145, 120, 150, 155, 220, 165, 100, 130, 115, 170, 110, 120, 85, 140, 150]
				}
			],
			chart: {
				type: "area",
				height: 150,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: "straight",
				width: 2
			},
			colors: [
				"#EE3E08"
			],
			fill: {
				type: 'gradient',
				gradient: {
					stops: [0, 90, 100],
					shadeIntensity: 1,
					opacityFrom: 0.5,
					opacityTo: 0.9
				}
			},
			xaxis: {
				categories: [
					"01 Jan",
					"02 Jan",
					"03 Jan",
					"04 Jan",
					"05 Jan",
					"06 Jan",
					"07 Jan",
					"08 Jan",
					"09 Jan",
					"10 Jan",
					"11 Jan",
					"12 Jan",
					"13 Jan",
					"14 Jan",
					"15 Jan",
					"16 Jan",
					"17 Jan",
					"18 Jan",
					"19 Jan",
					"20 Jan",
					"21 Jan",
					"22 Jan",
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 5,
				show: false,
				max: 250,
				min: 0,
				labels: {
					show: true,
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				},
				axisBorder: {
					show: true,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: true,
					color: '#ECEEF2'
				}
			},
			grid: {
				show: false,
				borderColor: "#ECEEF2"
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					size: 7,
					offsetX: -2,
					offsetY: -.5,
					shape: 'diamond'
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#impressions"), options);
		chart.render();
	}

	/* Sessions JS*/
	const getSessionsId = document.getElementById('sessions');
	if (getSessionsId) {
		var options = { 
			series: [
				{
					name: "Sessions",
					data: [110, 120, 85, 130, 145, 120, 150, 155, 100, 130, 115, 170, 220, 165, 140, 150, 100, 110, 90, 160, 125, 105]
				}
			],
			chart: {
				type: "area",
				height: 150,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: "straight",
				width: 2
			},
			colors: [
				"#9135E8"
			],
			fill: {
				type: 'gradient',
				gradient: {
					stops: [0, 90, 100],
					shadeIntensity: 1,
					opacityFrom: 0.5,
					opacityTo: 0.9
				}
			},
			xaxis: {
				categories: [
					"01 Jan",
					"02 Jan",
					"03 Jan",
					"04 Jan",
					"05 Jan",
					"06 Jan",
					"07 Jan",
					"08 Jan",
					"09 Jan",
					"10 Jan",
					"11 Jan",
					"12 Jan",
					"13 Jan",
					"14 Jan",
					"15 Jan",
					"16 Jan",
					"17 Jan",
					"18 Jan",
					"19 Jan",
					"20 Jan",
					"21 Jan",
					"22 Jan",
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 5,
				show: false,
				max: 250,
				min: 0,
				labels: {
					show: true,
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				},
				axisBorder: {
					show: true,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: true,
					color: '#ECEEF2'
				}
			},
			grid: {
				show: false,
				borderColor: "#ECEEF2"
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					size: 7,
					offsetX: -2,
					offsetY: -.5,
					shape: 'diamond'
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#sessions"), options);
		chart.render();
	}

	/* Sessions by Channel JS*/
	const getSessionsByChannelId = document.getElementById('sessions_by_channel');
	if (getSessionsByChannelId) {
		var options = {
			series: [976, 651, 818, 459, 320, 209],
			chart: {
				height: 274,
				type: "donut"
			},
			labels: [
				"Email", "Organic Search", "Direct Browse", "Paid Search", "Social", "Referral"
			],
			colors: [
				"#605DFF", "#3584FC", "#AD63F6", "#0dcaf0", "#25B003", "#FD5812"
			],
			stroke: {
				width: 1,
				show: true,
				colors: ["#ffffff"]
			},
			legend: {
				show: false,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					shape: 'diamond',
				}
			},
			plotOptions: {
				pie: {
					expandOnClick: false,
					donut: {
						labels: {
							show: true,
							name: {
								color: '#64748B'
							},
							value: {
								show: true,
								color: '#3A4252',
								fontSize: '28px',
								fontWeight: '600'
							},
							total: {
								show: true,
								color: '#64748B'
							}
						}
					}
				}
			},
			dataLabels: {
				enabled: false
			},
			tooltip: {
				enabled: false
			}
		}
		var chart = new ApexCharts(document.querySelector("#sessions_by_channel"), options);
		chart.render();
	}


	//**<---- Dashboard Crypto Charts ---->**//


	/* Bitcoin JS*/
	const getBitcoinId = document.getElementById('bitcoin');
	if (getBitcoinId) {
		var options = {
			series: [
				{
					name: "Price",
					data: [90, 130, 95, 140, 110, 120, 85, 170]
				}
			],
			chart: {
				type: "area",
				height: 120,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: "straight",
				width: 2
			},
			colors: [
				"#605DFF"
			],
			fill: {
				type: 'gradient',
				gradient: {
					stops: [0, 90, 100],
					shadeIntensity: 1,
					opacityFrom: 0.5,
					opacityTo: 0.9
				}
			},
			xaxis: {
				categories: [
					"01 Jan",
					"02 Jan",
					"03 Jan",
					"04 Jan",
					"05 Jan",
					"06 Jan",
					"07 Jan",
					"08 Jan"
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 5,
				show: false,
				max: 170,
				min: 0,
				labels: {
					show: true,
					style: {
						colors: "#64748B",
						fontSize: "12px"
					},
					formatter: (val) => {
						return '$' + val + 'K'
					}
				},
				axisBorder: {
					show: true,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: true,
					color: '#ECEEF2'
				}
			},
			grid: {
				show: false,
				borderColor: "#ECEEF2"
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					size: 7,
					offsetX: -2,
					offsetY: -.5,
					shape: 'diamond'
				}
			}
		}
		var chart = new ApexCharts(document.querySelector("#bitcoin"), options);
		chart.render();
	}

	/* Ethereum JS*/
	const getEthereumId = document.getElementById('ethereum');
	if (getEthereumId) {
		var options = {
			series: [
				{
					name: "Price",
					data: [90, 130, 140, 110, 120, 95, 85, 170]
				}
			],
			chart: {
				type: "area",
				height: 120,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: "straight",
				width: 2
			},
			colors: [
				"#AD63F6"
			],
			fill: {
				type: 'gradient',
				gradient: {
					stops: [0, 90, 100],
					shadeIntensity: 1,
					opacityFrom: 0.5,
					opacityTo: 0.9
				}
			},
			xaxis: {
				categories: [
					"01 Jan",
					"02 Jan",
					"03 Jan",
					"04 Jan",
					"05 Jan",
					"06 Jan",
					"07 Jan",
					"08 Jan"
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					},
				}
			},
			yaxis: {
				tickAmount: 5,
				show: false,
				max: 170,
				min: 0,
				labels: {
					show: true,
					style: {
						colors: "#64748B",
						fontSize: "12px"
					},
					formatter: (val) => {
						return '$' + val + 'K'
					}
				},
				axisBorder: {
					show: true,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: true,
					color: '#ECEEF2'
				}
			},
			grid: {
				show: false,
				borderColor: "#ECEEF2"
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					size: 7,
					offsetX: -2,
					offsetY: -.5,
					shape: 'diamond'
				}
			}
		}
		var chart = new ApexCharts(document.querySelector("#ethereum"), options);
		chart.render();
	}

	/* Solana  JS*/
	const getSolanaId = document.getElementById('solana');
	if (getSolanaId) {
		var options = {
			series: [
				{
					name: "Price",
					data: [90, 85, 170, 130, 95, 140, 110, 120]
				}
			],
			chart: {
				type: "area",
				height: 120,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: "straight",
				width: 2
			},
			colors: [
				"#FD5812"
			],
			fill: {
				type: 'gradient',
				gradient: {
					stops: [0, 90, 100],
					shadeIntensity: 1,
					opacityFrom: 0.5,
					opacityTo: 0.9
				}
			},
			xaxis: {
				categories: [
					"01 Jan",
					"02 Jan",
					"03 Jan",
					"04 Jan",
					"05 Jan",
					"06 Jan",
					"07 Jan",
					"08 Jan"
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 5,
				show: false,
				max: 170,
				min: 0,
				labels: {
					show: true,
					style: {
						colors: "#64748B",
						fontSize: "12px"
					},
					formatter: (val) => {
						return '$' + val + 'K'
					}
				},
				axisBorder: {
					show: true,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: true,
					color: '#ECEEF2'
				}
			},
			grid: {
				show: false,
				borderColor: "#ECEEF2"
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					size: 7,
					offsetX: -2,
					offsetY: -.5,
					shape: 'diamond'
				}
			}
		}
		var chart = new ApexCharts(document.querySelector("#solana"), options);
		chart.render();
	}

	/* Binance  JS*/
	const getBinanceId = document.getElementById('binance');
	if (getBinanceId) {
		var options = {
			series: [
				{
					name: "Price",
					data: [110, 120, 85, 170, 90, 130, 95, 140]
				}
			],
			chart: {
				type: "area",
				height: 120,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: "straight",
				width: 2
			},
			colors: [
				"#37D80A"
			],
			fill: {
				type: 'gradient',
				gradient: {
					stops: [0, 90, 100],
					shadeIntensity: 1,
					opacityFrom: 0.5,
					opacityTo: 0.9
				}
			},
			xaxis: {
				categories: [
					"01 Jan",
					"02 Jan",
					"03 Jan",
					"04 Jan",
					"05 Jan",
					"06 Jan",
					"07 Jan",
					"08 Jan"
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 5,
				show: false,
				max: 170,
				min: 0,
				labels: {
					show: true,
					style: {
						colors: "#64748B",
						fontSize: "12px"
					},
					formatter: (val) => {
						return '$' + val + 'K'
					}
				},
				axisBorder: {
					show: true,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: true,
					color: '#ECEEF2'
				}
			},
			grid: {
				show: false,
				borderColor: "#ECEEF2"
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					size: 7,
					offsetX: -2,
					offsetY: -.5,
					shape: 'diamond'
				}
			}
		}
		var chart = new ApexCharts(document.querySelector("#binance"), options);
		chart.render();
	}

	/* Cardano JS*/
	const getCardanoId = document.getElementById('cardano');
	if (getCardanoId) {
		var options = {
			series: [
				{
					name: "Price",
					data: [90, 130, 95, 140, 110, 120, 85, 170]
				}
			],
			chart: {
				type: "area",
				height: 120,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: "straight",
				width: 2
			},
			colors: [
				"#f7931a"
			],
			fill: {
				type: 'gradient',
				gradient: {
					stops: [0, 90, 100],
					shadeIntensity: 1,
					opacityFrom: 0.5,
					opacityTo: 0.9
				}
			},
			xaxis: {
				categories: [
					"01 Jan",
					"02 Jan",
					"03 Jan",
					"04 Jan",
					"05 Jan",
					"06 Jan",
					"07 Jan",
					"08 Jan"
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 5,
				show: false,
				max: 170,
				min: 0,
				labels: {
					show: true,
					style: {
						colors: "#64748B",
						fontSize: "12px"
					},
					formatter: (val) => {
						return '$' + val + 'K'
					}
				},
				axisBorder: {
					show: true,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: true,
					color: '#ECEEF2'
				}
			},
			grid: {
				show: false,
				borderColor: "#ECEEF2"
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					size: 7,
					offsetX: -2,
					offsetY: -.5,
					shape: 'diamond'
				}
			}
		}
		var chart = new ApexCharts(document.querySelector("#cardano"), options);
		chart.render();
	}

	/* Market Price Statistics JS*/
	const getMarketPriceStatisticsId = document.getElementById('market_price_statistics');
	if (getMarketPriceStatisticsId) {
		var options = {
			series: [
				{
					name: "Price",
					data: [
						{
							x: new Date(1538778600000),
							y: [6629.81, 6650.5, 6623.04, 6633.33]
						},
						{
							x: new Date(1538780400000),
							y: [6632.01, 6643.59, 6620, 6630.11]
						},
						{
							x: new Date(1538782200000),
							y: [6630.71, 6648.95, 6623.34, 6635.65]
						},
						{
							x: new Date(1538784000000),
							y: [6635.65, 6651, 6629.67, 6638.24]
						},
						{
							x: new Date(1538785800000),
							y: [6638.24, 6640, 6620, 6624.47]
						},
						{
							x: new Date(1538787600000),
							y: [6624.53, 6636.03, 6621.68, 6624.31]
						},
						{
							x: new Date(1538789400000),
							y: [6624.61, 6632.2, 6617, 6626.02]
						},
						{
							x: new Date(1538791200000),
							y: [6627, 6627.62, 6584.22, 6603.02]
						},
						{
							x: new Date(1538793000000),
							y: [6605, 6608.03, 6598.95, 6604.01]
						},
						{
							x: new Date(1538794800000),
							y: [6604.5, 6614.4, 6602.26, 6608.02]
						},
						{
							x: new Date(1538796600000),
							y: [6608.02, 6610.68, 6601.99, 6608.91]
						},
						{
							x: new Date(1538798400000),
							y: [6608.91, 6618.99, 6608.01, 6612]
						},
						{
							x: new Date(1538800200000),
							y: [6612, 6615.13, 6605.09, 6612]
						},
						{
							x: new Date(1538802000000),
							y: [6612, 6624.12, 6608.43, 6622.95]
						},
						{
							x: new Date(1538803800000),
							y: [6623.91, 6623.91, 6615, 6615.67]
						},
						{
							x: new Date(1538805600000),
							y: [6618.69, 6618.74, 6610, 6610.4]
						},
						{
							x: new Date(1538807400000),
							y: [6611, 6622.78, 6610.4, 6614.9]
						},
						{
							x: new Date(1538809200000),
							y: [6614.9, 6626.2, 6613.33, 6623.45]
						},
						{
							x: new Date(1538811000000),
							y: [6623.48, 6627, 6618.38, 6620.35]
						},
						{
							x: new Date(1538812800000),
							y: [6619.43, 6620.35, 6610.05, 6615.53]
						},
						{
							x: new Date(1538814600000),
							y: [6615.53, 6617.93, 6610, 6615.19]
						},
						{
							x: new Date(1538816400000),
							y: [6615.19, 6621.6, 6608.2, 6620]
						},
						{
							x: new Date(1538818200000),
							y: [6619.54, 6625.17, 6614.15, 6620]
						},
						{
							x: new Date(1538820000000),
							y: [6620.33, 6634.15, 6617.24, 6624.61]
						},
						{
							x: new Date(1538821800000),
							y: [6625.95, 6626, 6611.66, 6617.58]
						},
						{
							x: new Date(1538823600000),
							y: [6619, 6625.97, 6595.27, 6598.86]
						},
						{
							x: new Date(1538825400000),
							y: [6598.86, 6598.88, 6570, 6587.16]
						},
						{
							x: new Date(1538827200000),
							y: [6588.86, 6600, 6580, 6593.4]
						},
						{
							x: new Date(1538829000000),
							y: [6593.99, 6598.89, 6585, 6587.81]
						},
						{
							x: new Date(1538830800000),
							y: [6587.81, 6592.73, 6567.14, 6578]
						},
						{
							x: new Date(1538832600000),
							y: [6578.35, 6581.72, 6567.39, 6579]
						},
						{
							x: new Date(1538834400000),
							y: [6579.38, 6580.92, 6566.77, 6575.96]
						},
						{
							x: new Date(1538836200000),
							y: [6575.96, 6589, 6571.77, 6588.92]
						},
						{
							x: new Date(1538838000000),
							y: [6588.92, 6594, 6577.55, 6589.22]
						},
						{
							x: new Date(1538839800000),
							y: [6589.3, 6598.89, 6589.1, 6596.08]
						},
						{
							x: new Date(1538841600000),
							y: [6597.5, 6600, 6588.39, 6596.25]
						},
						{
							x: new Date(1538843400000),
							y: [6598.03, 6600, 6588.73, 6595.97]
						},
						{
							x: new Date(1538845200000),
							y: [6595.97, 6602.01, 6588.17, 6602]
						},
						{
							x: new Date(1538847000000),
							y: [6602, 6607, 6596.51, 6599.95]
						},
						{
							x: new Date(1538848800000),
							y: [6600.63, 6601.21, 6590.39, 6591.02]
						},
						{
							x: new Date(1538850600000),
							y: [6591.02, 6603.08, 6591, 6591]
						},
						{
							x: new Date(1538852400000),
							y: [6591, 6601.32, 6585, 6592]
						},
						{
							x: new Date(1538854200000),
							y: [6593.13, 6596.01, 6590, 6593.34]
						},
						{
							x: new Date(1538856000000),
							y: [6593.34, 6604.76, 6582.63, 6593.86]
						},
						{
							x: new Date(1538857800000),
							y: [6593.86, 6604.28, 6586.57, 6600.01]
						},
						{
							x: new Date(1538859600000),
							y: [6601.81, 6603.21, 6592.78, 6596.25]
						},
						{
							x: new Date(1538861400000),
							y: [6596.25, 6604.2, 6590, 6602.99]
						},
						{
							x: new Date(1538863200000),
							y: [6602.99, 6606, 6584.99, 6587.81]
						},
						{
							x: new Date(1538865000000),
							y: [6587.81, 6595, 6583.27, 6591.96]
						},
						{
							x: new Date(1538866800000),
							y: [6591.97, 6596.07, 6585, 6588.39]
						},
						{
							x: new Date(1538868600000),
							y: [6587.6, 6598.21, 6587.6, 6594.27]
						},
						{
							x: new Date(1538870400000),
							y: [6596.44, 6601, 6590, 6596.55]
						},
						{
							x: new Date(1538872200000),
							y: [6598.91, 6605, 6596.61, 6600.02]
						},
						{
							x: new Date(1538874000000),
							y: [6600.55, 6605, 6589.14, 6593.01]
						},
						{
							x: new Date(1538875800000),
							y: [6593.15, 6605, 6592, 6603.06]
						},
						{
							x: new Date(1538877600000),
							y: [6603.07, 6604.5, 6599.09, 6603.89]
						},
						{
							x: new Date(1538879400000),
							y: [6604.44, 6604.44, 6600, 6603.5]
						},
						{
							x: new Date(1538881200000),
							y: [6603.5, 6603.99, 6597.5, 6603.86]
						},
						{
							x: new Date(1538883000000),
							y: [6603.85, 6605, 6600, 6604.07]
						},
						{
							x: new Date(1538884800000),
							y: [6604.98, 6606, 6604.07, 6606]
						}
					]
				}
			],
			chart: {
				type: "candlestick",
				height: 345,
				toolbar: {
					show: false
				}
			},
			plotOptions: {
				candlestick: {
					colors: {
						upward: '#EE3E08',
						downward: '#4936F5'
					},
					wick: {
						useFillColor: true
					}
				}
			},
			xaxis: {
				type: "datetime",
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: true,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tooltip: {
					enabled: true
				},
				labels: {
					show: true,
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				}
			},
			grid: {
				show: true,
				borderColor: "#ECEEF2"
			}
		}
		var chart = new ApexCharts(document.querySelector("#market_price_statistics"), options);
		chart.render();
	}


	//**<---- Dashboard Sales Charts ---->**//


	/* Total Sales JS*/
	const getTotalSalesId = document.getElementById('total_sales');
	if (getTotalSalesId) {
		var options = {
			series: [
				{
					name: "Sales",
					data: [3, 7, 7, 10, 9, 7, 20]
				}
			],
			chart: {
				type: "area",
				height: 116,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			colors: [
				"#4936F5"
			],
			dataLabels: {
				enabled: false
			},
			grid: {
				show: false,
				borderColor: "#ECEEF2"
			},
			stroke: {
				curve: "straight",
				width: 1
			},
			xaxis: {
				categories: [
					"Mon",
					"Tue",
					"Wed",
					"Thu",
					"Fri",
					"Sat",
					"Sun"
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				// tickAmount: 6,
				show: false,
				// max: 150,
				// min: 0,
				labels: {
					formatter: (val) => {
						return '$' + val + 'k'
					},
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				}
			},
			legend: {
				show: false,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'left',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					size: 7,
					offsetX: -2,
					offsetY: -.5,
					shape: 'diamond'
				}
			}
		}
		var chart = new ApexCharts(document.querySelector("#total_sales"), options);
		chart.render();
	}

	/* Total Order JS*/
	const getTotalOrderId = document.getElementById('total_order');
	if (getTotalOrderId) {
		var options = {
			series: [
				{
					name: "Orders",
					data: [60, 50, 40, 50, 45, 30, 50, 35, 60, 45, 30, 60]
				}
			],
			chart: {
				type: "bar",
				height: 119,
				toolbar: {
					show: false
				}
			},
			colors: [
				"#AD63F6"
			],
			plotOptions: {
				bar: {
					borderRadius: 3,
					columnWidth: "9px",
					borderRadiusApplication: 'end',
					borderRadiusWhenStacked: 'last'
				}
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				width: 2,
				show: true,
				colors: ["transparent"]
			},
			xaxis: {
				categories: [
					"Jan",
					"Feb",
					"Mar",
					"Apr",
					"May",
					"Jun",
					"Jul",
					"Aug",
					"Sep",
					"Oct",
					"Nov",
					"Sep"
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				labels: {
					show: false,
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				}
			},
			fill: {
				opacity: 1
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					size: 7,
					offsetX: -2,
					offsetY: -.5,
					shape: 'diamond'
				}
			},
			grid: {
				show: false,
				borderColor: "#ECEEF2"
			}
		}
		var chart = new ApexCharts(document.querySelector("#total_order"), options);
		chart.render();
	}

	/* Total Profit JS*/
	const getTotalProfitId = document.getElementById('total_profit');
	if (getTotalProfitId) {
		var options = {
			series: [
				{
					name: "Profit",
					data: [3, 5, 10, 5, 9, 7, 15]
				}
			],
			chart: {
				type: "area",
				height: 120,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			colors: [
				"#3584FC"
			],
			dataLabels: {
				enabled: false
			},
			grid: {
				show: false,
				borderColor: "#ECEEF2"
			},
			stroke: {
				curve: "straight",
				width: 2
			},
			xaxis: {
				categories: [
					"Mon",
					"Tue",
					"Wed",
					"Thu",
					"Fri",
					"Sat",
					"Sun"
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				// tickAmount: 6,
				show: false,
				// max: 150,
				// min: 0,
				labels: {
					formatter: (val) => {
						return '$' + val + 'k'
					},
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				}
			},
			legend: {
				show: false,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'left',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					size: 7,
					offsetX: -2,
					offsetY: -.5,
					shape: 'diamond'
				}
			}
		}
		var chart = new ApexCharts(document.querySelector("#total_profit"), options);
		chart.render();
	}

	/* Total Revenues JS*/
	const getTotalRevenuesId = document.getElementById('total_revenues');
	if (getTotalRevenuesId) {
		var options = {
			series: [
				{
					name: "Revenue",
					data: [0, 35, 25, 45, 30, 45, 25, 45, 70]
				}
			],
			chart: {
				height: 120,
				type: "line",
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			dataLabels: {
				enabled: false
			},
			colors: [
				"#FD5812"
			],
			stroke: {
				width: 2,
				curve: "straight"
			},
			markers: {
				size: 3,
				strokeWidth: 0,
				hover: {
					size: 5
				}
			},
			grid: {
				show: false,
				borderColor: "#ECEEF2"
			},
			xaxis: {
				categories: [
					"Jan",
					"Feb",
					"Mar",
					"Apr",
					"May",
					"Jun",
					"Jul",
					"Aug",
					"Sep",
					"Oct",
					"Nov",
					"Dec"
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				// tickAmount: 6,
				show: false,
				// max: 150,
				// min: 0,
				labels: {
					formatter: (val) => {
						return '$' + val + 'k'
					},
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				}
			},
			legend: {
				show: false,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					size: 7,
					offsetX: -2,
					offsetY: -.5,
					shape: 'diamond'
				}
			}
		}
		var chart = new ApexCharts(document.querySelector("#total_revenues"), options);
		chart.render();
	}

	/* Recent Earnings JS*/
	const getRecentEarningsId = document.getElementById('recent_earnings');
	if (getRecentEarningsId) {
		var options = {
			series: [
				{
					name: "Gross Earnings",
					data: [44, 60, 41, 67, 22, 43]
				},
				{
					name: "Tax Withheld",
					data: [13, 30, 20, 8, 13, 27]
				},
				{
					name: "Net Earnings",
					data: [11, 20, 15, 15, 21, 14]
				},
			],
			chart: {
				type: "bar",
				height: 402,
				stacked: true,
				toolbar: {
					show: false
				},
				zoom: {
					enabled: true
				}
			},
			plotOptions: {
				bar: {
					borderRadius: 5,
					horizontal: false,
					columnWidth: '28px',
					borderRadiusApplication: 'end'
				}
			},
			dataLabels: {
				enabled: false
			},
			colors: [
				"#605DFF", "#9CAAFF", "#DDE4FF"
			],
			xaxis: {
				categories: [
					"Sun",
					"Mon",
					"Tue",
					"Wed",
					"Thu",
					"Fri"
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: true,
					color: '#D5D9E2'
				},
				labels: {
					show: true,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 5,
				max: 125,
				min: 0,
				labels: {
					formatter: (val) => {
						return '$' + val + 'K'
					},
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				},
				axisBorder: {
					show: true,
					color: '#D5D9E2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				}
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'left',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					size: 7,
					offsetX: -2,
					offsetY: -.5,
					shape: 'diamond'
				}
			},
			fill: {
				opacity: 1
			},
			grid: {
				show: true,
				strokeDashArray: 10,
				borderColor: "#ECEEF2"
			}
		}
		var chart = new ApexCharts(document.querySelector("#recent_earnings"), options);
		chart.render();
	}

	/* Real-Time Sales JS*/
	const getRealTimeSalesId = document.getElementById('real_time_sales');
	if (getRealTimeSalesId) {
		var options = {
			series: [
				{
					name: "Sales",
					data: [2.3, 3.1, 4.0, 10.1, 4.0, 3.6, 3.2, 2.3]
				}
			],
			chart: {
				height: 240,
				type: "bar",
				toolbar: {
					show: false
				}
			},
			plotOptions: {
				bar: {
					borderRadius: 5,
					horizontal: false,
					columnWidth: '22px',
					borderRadiusApplication: 'around',
					borderRadiusWhenStacked: 'all',
					dataLabels: {
						position: "top" // top, center, bottom
					}
				}
			},
			dataLabels: {
				enabled: true,
				formatter: function(val) {
					return val + "%";
				},
				offsetY: -20,
				style: {
					fontSize: "10px",
					colors: ["#64748B"]
				}
			},
			xaxis: {
				show: false,
				categories: [
					"Sun",
					"Mon",
					"Tue",
					"Wed",
					"Thu",
					"Fri",
					"Sat",
					"Sun"
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			colors: [
				"#3584FC"
			],
			yaxis: {
				tickAmount: 5,
				show: false,
				max: 11,
				min: 0,
				labels: {
					formatter: (val) => {
						return val + '%'
					},
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				}
			},
			grid: {
				show: false,
				borderColor: "#ECEEF2"
			}
		}
		var chart = new ApexCharts(document.querySelector("#real_time_sales"), options);
		chart.render();
	}

	/* Sales Overview JS*/
	const getSalesOverviewId = document.getElementById('sales_overview');
	if (getSalesOverviewId) {
		var options = {
			series: [
				{
					name: "Sales",
					data: [80, 50, 30, 40, 100, 20]
				},
				{
					name: "Sales",
					data: [20, 30, 40, 80, 20, 80]
				},
				{
					name: "Sales",
					data: [30, 70, 80, 15, 45, 10]
				},
			],
			chart: {
				height: 340,
				type: "radar",
				toolbar: {
					show: false
				}
			},
			dataLabels: {
				enabled: false
			},
			xaxis: {
				categories: ["2019", "2020", "2021", "2022", "2023", "2024"],
				labels: {
					show: true,
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				}
			},
			markers: {
				colors: 'transparent',
				strokeWidth: 0
			},
			colors: [
				"#605DFF", "#37D80A", "#FD5812"
			],
			yaxis: {
				show: false
			},
			tooltip: {
				y: {
					formatter: function(val) {
						return val + "%";
					}
				}
			},
			legend: {
				show: false,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					size: 7,
					offsetX: -2,
					offsetY: -.5,
					shape: 'diamond'
				}
			}
		}
		var chart = new ApexCharts(document.querySelector("#sales_overview"), options);
		chart.render();
	}

	/* Gross Earnings JS*/
	const getGrossEarningsId = document.getElementById('gross_earnings');
	if (getGrossEarningsId) {
		var options = {
			series: [
				{
					name: "Earnings",
					data: [4, 3, 10, 9, 29, 19, 22, 9, 12, 7]
				}
			],
			chart: {
				height: 207,
				type: "line",
				toolbar: {
					show: false
				}
			},
			stroke: {
				width: 4,
				curve: "smooth"
			},
			xaxis: {
				categories: [
					"1W",
					"2W",
					"3W",
					"4W",
					"5W",
					"6W",
					"7W",
					"8W",
					"9W",
					"10W"
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: true,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			grid: {
				show: false,
				borderColor: "#ECEEF2"
			},
			colors: [
				'#9135E8'
			],
			yaxis: {
				show: false,
				labels: {
					formatter: (val) => {
						return '$' + val + 'K'
					},
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				}
			}
		}
		var chart = new ApexCharts(document.querySelector("#gross_earnings"), options);
		chart.render();
	}


	//**<---- Dashboard Hospital ---->**//


	/* Overall Visitors JS*/
	const getOverallVisitorsId = document.getElementById('overall_visitors');
	if (getOverallVisitorsId) {
		var options = {
			series: [
				{
					name: "Visitors",
					data: [30, 70, 50, 75, 40, 80, 50, 100]
				}
			],
			chart: {
				type: "area",
				height: 160,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: "straight",
				width: 2
			},
			colors: [
				"#9747FF"
			],
			fill: {
				type: 'gradient',
				gradient: {
					stops: [0, 90, 100],
					shadeIntensity: 1,
					opacityFrom: 0.5,
					opacityTo: 0.9
				}
			},
			xaxis: {
				categories: [
					"01 Jan",
					"02 Jan",
					"03 Jan",
					"04 Jan",
					"05 Jan",
					"06 Jan",
					"07 Jan",
					"08 Jan"
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 5,
				show: false,
				max: 100,
				min: 0,
				labels: {
					show: true,
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				},
				axisBorder: {
					show: true,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: true,
					color: '#ECEEF2'
				}
			},
			grid: {
				show: false,
				borderColor: "#ECEEF2"
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					size: 7,
					offsetX: -2,
					offsetY: -.5,
					shape: 'diamond'
				}
			}
		}
		var chart = new ApexCharts(document.querySelector("#overall_visitors"), options);
		chart.render();
	}
	
	/* Patients Last 7 Days Service JS*/
	const getPatientsLast7DaysServiceId = document.getElementById('patients_last_7_days_service');
	if (getPatientsLast7DaysServiceId) {
		var options = {
			series: [
				{
					name: "Patients",
					data: [60, 50, 40, 50, 45, 40, 60]
				}
			],
			chart: {
				type: "bar",
				height: 100,
				toolbar: {
					show: false
				}
			},
			colors: [
				"#FE7A36"
			],
			plotOptions: {
				bar: {
					borderRadius: 3,
					columnWidth: "9px",
				}
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				width: 2,
				show: true,
				colors: ["transparent"]
			},
			xaxis: {
				categories: [
					"Sun",
					"Mon",
					"Tue",
					"Wed",
					"Thu",
					"Fri",
					"Sat"
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				max: 60,
				min: 0,
				labels: {
					show: false,
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				}
			},
			fill: {
				opacity: 1
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					size: 7,
					offsetX: -2,
					offsetY: -.5,
					shape: 'diamond'
				}
			},
			grid: {
				show: false,
				borderColor: "#ECEEF2"
			}
		}
		var chart = new ApexCharts(document.querySelector("#patients_last_7_days_service"), options);
		chart.render();
	}

	/* Patient Admissions & Discharges JS*/
	const getPatientAdmissionsDischargesId = document.getElementById('patient_admissions_discharges');
	if (getPatientAdmissionsDischargesId) {
		var options = {
			series: [
				{
					name: "Admissions",
					data: [170, 420, 300, 550, 550, 650, 820]
				},
				{
					name: "Discharges",
					data: [320, 300, 650, 400, 750, 650, 600]
				}
			],
			chart: {
				type: "area",
				height: 345,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			colors: [
				"#4936F5", "#EC1F00"
			],
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: "smooth",
				width: [2, 2]
			},
			grid: {
				show: true,
				borderColor: "#ECEEF2",
				strokeDashArray: 10,
				xaxis: {
					lines: {
						show: true
					}
				}
			},
			fill: {
				type: 'gradient',
				gradient: {
					stops: [0, 90, 100],
					shadeIntensity: 1,
					opacityFrom: 0,
					opacityTo: 0.5
				}
			},
			xaxis: {
				categories: [
					"Sun",
					"Mon",
					"Tue",
					"Wed",
					"Thu",
					"Fri",
					"Sat"
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: true,
					color: '#D5D9E2'
				},
				labels: {
					show: true,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 5,
				max: 1000,
				min: 0,
				labels: {
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				},
				axisBorder: {
					show: true,
					color: '#D5D9E2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				}
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'left',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					size: 7,
					offsetX: -2,
					offsetY: -.5,
					shape: 'diamond'
				}
			}
		}
		var chart = new ApexCharts(document.querySelector("#patient_admissions_discharges"), options);
		chart.render();
	}

	/* Emergency Room Visits JS*/
	const getEmergencyRoomVisitsId = document.getElementById('emergency_room_visits');
	if (getEmergencyRoomVisitsId) {

		function generateData(count, yrange) {
			var i = 0;
			var series = [];
			while (i < count) {
				var x = (i + 1).toString();
				var y =
					Math.floor(Math.random() * (yrange.max - yrange.min + 1)) + yrange.min;
			
				series.push({
					x: x,
					y: y
				});
				i++;
			}
			return series;
		}
		  
		var options = {
			colors: [
				"#605DFF",
				"#605DFF",
				"#605DFF",
				"#605DFF",
				"#605DFF",
				"#605DFF",
				"#605DFF",
				"#605DFF",
				"#605DFF"
			],
			plotOptions: {
				heatmap: {
					shadeIntensity: 1
				}
			},
			dataLabels: {
			  	enabled: false
			},
			series: [
				{
					name: "08 PM",
					data: generateData(20, {
						min: -30,
						max: 55
					})
				},
				{
					name: "09 PM",
					data: generateData(20, {
						min: -30,
						max: 55
					})
				},
				{
					name: "10 PM",
					data: generateData(20, {
						min: 0,
						max: 0
					})
				},
				{
					name: "11 PM",
					data: generateData(20, {
						min: -30,
						max: 55
					})
				},
				{
					name: "12 PM",
					data: generateData(20, {
						min: -30,
						max: 55
					})
				},
				{
					name: "13 PM",
					data: generateData(20, {
						min: -30,
						max: 55
					})
				},
				{
					name: "14 PM",
					data: generateData(20, {
						min: -30,
						max: 55
					})
			  	}
			],
			chart: {
				height: 235,
				type: "heatmap",
				toolbar: {
					show: false
				}
			},
			dataLabels: {
				enabled: false
			},
			colors: [
				"#605DFF"
			],
			grid: {
				show: false,
				borderColor: "#ECEEF2"
			},
			xaxis: {
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: true,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				},
				categories: [
					"W1",
					"W2",
					"W3",
					"W4",
					"W5",
					"W6",
					"W7",
					"W8",
					"W9",
					"W10",
					"W11",
					"W12",
					"W13",
					"W14",
					"W15",
					"W16",
					"W17",
					"W18",
					"W19",
					"W20"
				],
			},
			yaxis: {
				labels: {
					show: true,
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				}
			}
			
		};

		var chart = new ApexCharts(document.querySelector("#emergency_room_visits"), options);
		chart.render();
	}

	/* Critical Patients JS*/
	const getCriticalPatientsId = document.getElementById('critical_patients');
	if (getCriticalPatientsId) {
		  
		var options = {
			series: [
				{
					name: "Orthopedics",
					data: [10, 15, 21, 25, 19, 15, 25, 20, 20, 15, 21, 25, 17, 18, 15, 20, 15, 20, 18, 13]
				},
				{
					name: "Cardiology",
					data: [3, 7, 7, 10, 9, 7, 15, 3, 7, 7, 10, 9, 7, 13, 3, 7, 7, 10, 9, 7]
				}
			],
			chart: {
				type: "area",
				height: 130,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			colors: [
				"#FD5812", "#796DF6"
			],
			dataLabels: {
				enabled: false
			},
			grid: {
				show: false,
				borderColor: "#ECEEF2"
			},
			stroke: {
				curve: "straight",
				width: 2
			},
			xaxis: {
				categories: [
					"01 Jan",
					"02 Jan",
					"03 Jan",
					"04 Jan",
					"05 Jan",
					"06 Jan",
					"07 Jan",
					"08 Jan",
					"09 Jan",
					"10 Jan",
					"11 Jan",
					"12 Jan",
					"13 Jan",
					"14 Jan",
					"15 Jan",
					"16 Jan",
					"17 Jan",
					"18 Jan",
					"19 Jan",
					"20 Jan"
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				// tickAmount: 6,
				show: false,
				max: 25,
				min: 0,
				labels: {
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				}
			},
			legend: {
				show: false,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'left',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					size: 7,
					offsetX: -2,
					offsetY: -.5,
					shape: 'diamond'
				}
			}
		};

		var chart = new ApexCharts(document.querySelector("#critical_patients"), options);
		chart.render();
	}

	/* Bed Occupancy Rate JS*/
	const getBedOccupancyRateId = document.getElementById('bed_occupancy_rate');
	if (getBedOccupancyRateId) {
		  
		var options = {
			series: [1275, 825, 450],
			chart: {
				height: 160,
				type: "donut"
			},
			labels: [
				"Total Beds", "Occupied Beds", "Available Beds"
			],
			colors: [
				"#1F64F1", "#BF85FB", "#37D80A"
			],
			stroke: {
				width: 1,
				show: true,
				colors: ["#ffffff"]
			},
			legend: {
				show: false,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					size: 7,
					offsetX: -2,
					offsetY: -.5,
					shape: 'diamond'
				}
			},
			plotOptions: {
				pie: {
					expandOnClick: false,
					donut: {
						labels: {
							show: false,
							name: {
								color: '#64748B'
							},
							value: {
								show: true,
								color: '#3A4252',
								fontSize: '22px',
								fontWeight: '600'
							},
							total: {
								show: true,
								color: '#64748B'
							}
						}
					}
				}
			},
			dataLabels: {
				enabled: false
			},
			tooltip: {
				enabled: true
			}
		};
		var chart = new ApexCharts(document.querySelector("#bed_occupancy_rate"), options);
		chart.render();
	}
	
	/* Patient by Age JS*/
	const getPatientByAgeId = document.getElementById('patient_by_age');
	if (getPatientByAgeId) {
		  
		var options = {
			series: [
				30, 40, 20, 10
			],
			chart: {
				height: 327,
				type: "pie"
			},
			labels: [
				"0-18 Years", "19-40 Years", "41-60 Years", "60+ Years"
			],
			colors: [
				"#AD63F6", "#605DFF", "#25B003", "#3584FC"
			],
			dataLabels: {
				enabled: true,
				formatter: function (val) {
					return val + "%";
				},
				dropShadow: {
					enabled: false
				}
			},
			plotOptions: {
				pie: {
					expandOnClick: false
				}
			},
			stroke: {
				width: 1,
				show: true,
				colors: ["#ffffff"]
			},
			legend: {
				show: true,
				fontSize: '12px',
				position: 'bottom',
				horizontalAlign: 'center',
				itemMargin: {
					horizontal: 8,
					vertical: 7
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					size: 7,
					offsetX: -2,
					offsetY: -.5,
					shape: 'diamond'
				},
				formatter: function(seriesName, opts) {
					return [seriesName, ":", opts.w.globals.series[opts.seriesIndex], "%"]
				},
				onItemClick: {
					toggleDataSeries: false
				},
				onItemHover: {
					highlightDataSeries: false
				}
			},
			tooltip: {
				y: {
					formatter: function(val) {
						return val + "%";
					}
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#patient_by_age"), options);
		chart.render();
	}

	/* Total Earnings JS*/
	const getTotalEarningsId = document.getElementById('total_earnings');
	if (getTotalEarningsId) {
		  
		var options = {
			series: [
				{
					name: "Earnings",
					data: [3, 7, 7, 10, 9, 11, 20]
				}
			],
			chart: {
				type: "area",
				height: 95,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			colors: [
				"#3584FC"
			],
			dataLabels: {
				enabled: false
			},
			grid: {
				show: false,
				borderColor: "#ECEEF2"
			},
			stroke: {
				curve: "straight",
				width: 1
			},
			xaxis: {
				categories: [
					"Mon",
					"Tue",
					"Wed",
					"Thu",
					"Fri",
					"Sat",
					"Sun"
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				// tickAmount: 6,
				show: false,
				// max: 150,
				// min: 0,
				labels: {
					formatter: (val) => {
						return '$' + val + 'k'
					},
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				}
			},
			legend: {
				show: false,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'left',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					size: 7,
					offsetX: -2,
					offsetY: -.5,
					shape: 'diamond'
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#total_earnings"), options);
		chart.render();
	}


	//**<---- Dashboard Marketing ---->**//


	/* Performance Overview JS*/
	const gePerformanceOverviewId = document.getElementById('performance_overview');
	if (gePerformanceOverviewId) {

		function generateData(baseval, count, yrange) {
			var i = 0;
			var series = [];
			while (i < count) {
				var x = Math.floor(Math.random() * (750 - 1 + 1)) + 1;;
				var y = Math.floor(Math.random() * (yrange.max - yrange.min + 1)) + yrange.min;
				var z = Math.floor(Math.random() * (75 - 15 + 1)) + 15;
			
				series.push([x, y, z]);
				baseval += 86400000;
				i++;
			}
			return series;
		}
		var options = {
			series: [
				{
				  name: 'Social Campaigns',
					data: generateData(new Date('11 Feb 2017 GMT').getTime(), 20, {
						min: 10,
						max: 60
					})
				},
				{
					name: 'Email Newsletter',
					data: generateData(new Date('11 Feb 2017 GMT').getTime(), 20, {
						min: 10,
						max: 60
					})
				},
				{
					name: 'TV Campaign',
					data: generateData(new Date('11 Feb 2017 GMT').getTime(), 20, {
						min: 10,
						max: 60
					})
				},
				{
					name: 'Google Ads',
					data: generateData(new Date('11 Feb 2017 GMT').getTime(), 20, {
						min: 10,
						max: 60
					})
				},
				{
					name: 'Courses',
					data: generateData(new Date('11 Feb 2017 GMT').getTime(), 20, {
						min: 10,
						max: 60
					})
				},
				{
					name: 'Radio',
					data: generateData(new Date('11 Feb 2017 GMT').getTime(), 20, {
						min: 10,
						max: 60
					})
				},
			],
			colors: [
				"#757DFF", "#5DA8FF", "#BF85FB", "#1E8308", "#FE7A36", "#174EDE",
			],
				chart: {
				height: 351,
				type: 'bubble',
				toolbar: {
					show: false,
				}
			},
			dataLabels: {
				enabled: false
			},
			fill: {
				opacity: 0.8
			},
			grid: {
				borderColor: "#ECEEF2"
			},
			xaxis: {
				tickAmount: 10,
				type: 'category',
				axisTicks: {
					show: false,
					color: '#B1BBC8'
				},
				axisBorder: {
					show: false,
					color: '#B1BBC8'
				},
				labels: {
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 5,
				max: 100,
				min: 0,
				max: 70,
				labels: {
					formatter: (val) => {
						return '$' + val / 1 + 'K'
					},
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				},
			},
			legend: {
				show: true,
				position: 'bottom',
				fontSize: '12px',
				horizontalAlign: 'left',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					offsetX: -2,
					offsetY: -.5,
					size: 7,
					shape: "square", // circle, square, line, plus, cross
					radius: 5,
				},
				
			},
			tooltip: {
				y: {
					formatter: function(val) {
					return "$" + val + "k";
					}
				}
			},
		};
		var chart = new ApexCharts(document.querySelector("#performance_overview"), options);
		chart.render();

	}

	/* Instagram Campaigns JS*/
	const getInstagramCampaignsId = document.getElementById('instagram_campaigns');
	if (getInstagramCampaignsId) {
		var options = {
			series: [
				{
					name: "Campaign Budget",
					data: [50, 40, 30, 50, 40, 30, 50]
				},
				{
					name: "Followers Goal",
					data: [11, 32, 45, 32, 34, 52, 41]
				}
			],
			chart: {
				type: "area",
				height: 149,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			colors: [
				"#FF6D57", "#AD63F6"
			],
			dataLabels: {
				enabled: false
			},
			grid: {
				show: false,
				borderColor: "#ECEEF2"
			},
			stroke: {
				curve: "smooth",
				width: 2
			},
			xaxis: {
				categories: [
					"Mon",
					"Tue",
					"Wed",
					"Thu",
					"Fri",
					"Sat",
					"Sun"
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				// tickAmount: 6,
				show: false,
				// max: 150,
				// min: 0,
				labels: {
					formatter: (val) => {
						return '$' + val + 'k'
					},
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				}
			},
			legend: {
				show: false,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'left',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					size: 7,
					offsetX: -2,
					offsetY: -.5,
					shape: 'diamond'
				}
			}
		}
		var chart = new ApexCharts(document.querySelector("#instagram_campaigns"), options);
		chart.render();
	}

	/* Instagram Subscriber JS*/
	const getInstagramSubscriberId = document.getElementById('instagram_subscriber');
	if (getInstagramSubscriberId) {
		var options = {
			series: [
				{
					name: "Gained",
					data: [44, 60, 41, 67, 22, 43, 13, 30, 20, 8]
				},
				{
					name: "Lost",
					data: [13, 30, 20, 8, 13, 27, 44, 60, 41, 67]
				},
			],
			chart: {
				type: "bar",
				height: 389,
				stacked: true,
				toolbar: {
					show: false
				},
				zoom: {
					enabled: true
				}
			},
			plotOptions: {
				bar: {
					borderRadius: 5,
					horizontal: false,
					columnWidth: '28px',
					borderRadiusApplication: 'end'
				}
			},
			dataLabels: {
				enabled: false
			},
			colors: [
				"#605DFF", "#9CAAFF", "#DDE4FF"
			],
			xaxis: {
				categories: [
					"Oct 01",
					"Oct 02",
					"Oct 03",
					"Oct 04",
					"Oct 05",
					"Oct 06",
					"Oct 07",
					"Oct 07",
					"Oct 08",
					"Oct 09"
				],
				axisTicks: {
					show: false,
					color: '#8695AA'
				},
				axisBorder: {
					show: true,
					color: '#D5D9E2'
				},
				labels: {
					show: true,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 5,
				max: 125,
				min: 0,
				labels: {
					formatter: (val) => {
						return '' + val + 'K'
					},
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				},
				axisBorder: {
					show: true,
					color: '#D5D9E2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				}
			},
			legend: {
				show: true,
				position: 'bottom',
				fontSize: '12px',
				horizontalAlign: 'left',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					size: 7,
					offsetX: -2,
					offsetY: -.5,
					shape: 'diamond'
				}
			},
			fill: {
				opacity: 1
			},
			grid: {
				show: true,
				strokeDashArray: 0,
				borderColor: "#ECF0FF"
			}
		}
		var chart = new ApexCharts(document.querySelector("#instagram_subscriber"), options);
		chart.render();
	}


	//**<---- Dashboard NFT ---->**//


	/* Ethereum Rate JS */ 
	const getEthereumRateId = document.getElementById('ethereum_rate');
	if (getEthereumRateId) {
		var options = {
			series: [
				{
					name: "Ethereum Rate",
					data: [20, 40, 60, 60, 50, 30, 20, 20, 40, 40, 60, 60]
				}
			],
			chart: {
				type: "area",
				height: 253,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			colors: [
				"#3584FC"
			],
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: "stepline", //curve: ['straight', 'smooth', 'monotoneCubic', 'stepline']
				width: 3,
				lineCap: "10",
			},
			grid: {
				borderColor: '#ECF0FF', 
				strokeDashArray: 0,
				xaxis: {
					lines: {
						show: false,
					}
				},
			},
			fill: {
				type: 'gradient',
				gradient: {
					stops: [0, 90, 100],
					shadeIntensity: 1,
					opacityFrom: 0,
					opacityTo: 0.8
				}
			},
			xaxis: {
				categories: [
					"NOV 16",
					"17",
					"18",
					"19",
					"20",
					"21",
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 4,
				max: 80,
				min: 0,
				labels: {
					show: false,
					formatter: (val) => {
						return  val / 1 + 'K';
					},
					style: {
						colors: "#9C9AB6",
						fontSize: "12px",
						fontWeight: 500,
					}
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				}
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2
				}
			},
			tooltip: {
				y: {
					formatter: function(val) {
					return "$" + val + "K";
					}
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#ethereum_rate"), options);
		chart.render();
	}
	
	/* Ethereum Rate 2 JS */ 
	const getEthereumRate2Id = document.getElementById('ethereum_rate2');
	if (getEthereumRate2Id) {
		var options = {
			series: [
				{
					name: "Ethereum Rate",
					data: [60, 60, 40, 40, 20, 20, 30, 50, 60, 60, 40, 20],
				}
			],
			chart: {
				type: "area",
				height: 253,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			colors: [
				"#3584FC"
			],
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: "stepline", //curve: ['straight', 'smooth', 'monotoneCubic', 'stepline']
				width: 3,
				lineCap: "10",
			},
			grid: {
				borderColor: '#ECF0FF', 
				strokeDashArray: 0,
				xaxis: {
					lines: {
						show: false,
					}
				},
			},
			fill: {
				type: 'gradient',
				gradient: {
					stops: [0, 90, 100],
					shadeIntensity: 1,
					opacityFrom: 0,
					opacityTo: 0.8
				}
			},
			xaxis: {
				categories: [
					"NOV 16",
					"17",
					"18",
					"19",
					"20",
					"21",
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 4,
				max: 80,
				min: 0,
				labels: {
					show: false,
					formatter: (val) => {
						return  val / 1 + 'K';
					},
					style: {
						colors: "#9C9AB6",
						fontSize: "12px",
						fontWeight: 500,
					}
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				}
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2
				}
			},
			tooltip: {
				y: {
					formatter: function(val) {
					return "$" + val + "K";
					}
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#ethereum_rate2"), options);
		chart.render();
	} 

	/* Ethereum Rate 3 JS */ 
	const getEthereumRate3Id = document.getElementById('ethereum_rate3');
	if (getEthereumRate3Id) {
		var options = {
			series: [
				{
					name: "Ethereum Rate",
					data: [20, 40, 60, 60, 50, 30, 20, 20, 40, 40, 60, 60]
				}
			],
			chart: {
				type: "area",
				height: 253,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			colors: [
				"#3584FC"
			],
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: "stepline", //curve: ['straight', 'smooth', 'monotoneCubic', 'stepline']
				width: 3,
				lineCap: "10",
			},
			grid: {
				borderColor: '#ECF0FF', 
				strokeDashArray: 0,
				xaxis: {
					lines: {
						show: false,
					}
				},
			},
			fill: {
				type: 'gradient',
				gradient: {
					stops: [0, 90, 100],
					shadeIntensity: 1,
					opacityFrom: 0,
					opacityTo: 0.8
				}
			},
			xaxis: {
				categories: [
					"NOV 16",
					"17",
					"18",
					"19",
					"20",
					"21",
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 4,
				max: 80,
				min: 0,
				labels: {
					show: false,
					formatter: (val) => {
						return  val / 1 + 'K';
					},
					style: {
						colors: "#9C9AB6",
						fontSize: "12px",
						fontWeight: 500,
					}
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				}
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2
				}
			},
			tooltip: {
				y: {
					formatter: function(val) {
					return "$" + val + "K";
					}
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#ethereum_rate3"), options);
		chart.render();
	}

	/* Ethereum Rate 4 JS */ 
	const getEthereumRate4Id = document.getElementById('ethereum_rate4');
	if (getEthereumRate4Id) {
		var options = {
			series: [
				{
					name: "Ethereum Rate",
					data: [60, 60, 40, 40, 20, 20, 30, 50, 60, 60, 40, 20],
				}
			],
			chart: {
				type: "area",
				height: 253,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			colors: [
				"#3584FC"
			],
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: "stepline", //curve: ['straight', 'smooth', 'monotoneCubic', 'stepline']
				width: 3,
				lineCap: "10",
			},
			grid: {
				borderColor: '#ECF0FF', 
				strokeDashArray: 0,
				xaxis: {
					lines: {
						show: false,
					}
				},
			},
			fill: {
				type: 'gradient',
				gradient: {
					stops: [0, 90, 100],
					shadeIntensity: 1,
					opacityFrom: 0,
					opacityTo: 0.8
				}
			},
			xaxis: {
				categories: [
					"NOV 16",
					"17",
					"18",
					"19",
					"20",
					"21",
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 4,
				max: 80,
				min: 0,
				labels: {
					show: false,
					formatter: (val) => {
						return  val / 1 + 'K';
					},
					style: {
						colors: "#9C9AB6",
						fontSize: "12px",
						fontWeight: 500,
					}
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				}
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2
				}
			},
			tooltip: {
				y: {
					formatter: function(val) {
					return "$" + val + "K";
					}
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#ethereum_rate4"), options);
		chart.render();
	} 

	/* Ethereum Rate 5 JS */ 
	const getEthereumRate5Id = document.getElementById('ethereum_rate5');
	if (getEthereumRate5Id) {
		var options = {
			series: [
				{
					name: "Ethereum Rate",
					data: [20, 40, 60, 60, 50, 30, 20, 20, 40, 40, 60, 60]
				}
			],
			chart: {
				type: "area",
				height: 253,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			colors: [
				"#3584FC"
			],
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: "stepline", //curve: ['straight', 'smooth', 'monotoneCubic', 'stepline']
				width: 3,
				lineCap: "10",
			},
			grid: {
				borderColor: '#ECF0FF', 
				strokeDashArray: 0,
				xaxis: {
					lines: {
						show: false,
					}
				},
			},
			fill: {
				type: 'gradient',
				gradient: {
					stops: [0, 90, 100],
					shadeIntensity: 1,
					opacityFrom: 0,
					opacityTo: 0.8
				}
			},
			xaxis: {
				categories: [
					"NOV 16",
					"17",
					"18",
					"19",
					"20",
					"21",
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 4,
				max: 80,
				min: 0,
				labels: {
					show: false,
					formatter: (val) => {
						return  val / 1 + 'K';
					},
					style: {
						colors: "#9C9AB6",
						fontSize: "12px",
						fontWeight: 500,
					}
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				}
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2
				}
			},
			tooltip: {
				y: {
					formatter: function(val) {
					return "$" + val + "K";
					}
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#ethereum_rate5"), options);
		chart.render();
	}


	//**<---- Dashboard SAAS ---->**//


	/* Today Payment JS*/
	const getTodayPaymentId = document.getElementById('today_payment');
	if (getTodayPaymentId) {
		var options = {
			series: [
				{
					name: "Today’s Payment",
					data: [20, 50, 60, 30, 30, 40, 60, 70, 40, 50,]
				}
			],
			chart: {
				type: "area",
				height: 340,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false,
				}
			},
			colors: [
				"#FFFFFF",
			],
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: "monotoneCubic", //curve: ['straight', 'smooth', 'monotoneCubic', 'stepline']
				width: [3,],
			},
			grid: {
				borderColor: "#4E3FB9"
			},
			fill: {
				type: 'gradient',
				gradient: {
					stops: [0, 90, 100],
					shadeIntensity: 1,
					opacityFrom: 0,
					opacityTo: 0.5
				}
			},
			xaxis: {
				categories: [
					"09:00",
					"10:00",
					"11:00",
					"12:00",
					"13:00",
					"14:00",
					"15:00",
					"16:00",
					"17:00",
					"18:00",
				],
				axisTicks: {
					show: false,
					color: '#B1BBC8'
				},
				axisBorder: {
					show: false,
					color: '#B1BBC8'
				},
				labels: {
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 5,
				max: 100,
				min: 0,
				labels: {
					formatter: (val) => {
						return '$' + val / 1 + 'K'
					},
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			legend: {
				show: false,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'left',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			},
			tooltip: {
				y: {
					formatter: function(val) {
					return "$" + val + "k";
					}
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#today_payment"), options);
		chart.render();
	}

	/* Active User JS*/
	const getActiveUserId = document.getElementById('active_user');
	if (getActiveUserId) {
		  
		var options = {
			series: [1000, 825, 825],
			chart: {
				height: 85,
				type: "donut"
			},
			labels: [
				"Active User", "Total User", "Bunch User"
			],
			colors: [
				"#5DA8FF", "#58F229", "#757DFF"
			],
			stroke: {
				width: 1,
				show: true,
				colors: ["#ffffff"]
			},
			legend: {
				show: false,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					size: 7,
					offsetX: -2,
					offsetY: -.5,
					shape: 'diamond'
				}
			},
			plotOptions: {
				pie: {
					expandOnClick: false,
					donut: {
						labels: {
							show: false,
							name: {
								color: '#64748B'
							},
							value: {
								show: true,
								color: '#3A4252',
								fontSize: '22px',
								fontWeight: '600'
							},
							total: {
								show: true,
								color: '#64748B'
							}
						}
					}
				}
			},
			dataLabels: {
				enabled: false
			},
			tooltip: {
				enabled: true
			}
		};
		var chart = new ApexCharts(document.querySelector("#active_user"), options);
		chart.render();
	}

	/* Revenue Two JS*/
	const getRevenueTwoId = document.getElementById('revenue_two');
	if (getRevenueTwoId) {
		var options = {
			series: [
				{
					name: "Revenue",
					data: [30, 70, 50, 80, 60, 100]
				}
			],
			chart: {
				type: "area",
				height: 130,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: "straight",
				width: 2
			},
			colors: [
				"#868DFF"
			],
			fill: {
				type: 'gradient',
				gradient: {
					stops: [0, 90, 100],
					shadeIntensity: 1,
					opacityFrom: 0.5,
					opacityTo: 0.9
				}
			},
			xaxis: {
				categories: [
					"01 Jan",
					"02 Jan",
					"03 Jan",
					"04 Jan",
					"05 Jan",
					"06 Jan",
					"07 Jan",
					"08 Jan"
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 5,
				show: false,
				max: 100,
				min: 0,
				labels: {
					show: true,
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				},
				axisBorder: {
					show: true,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: true,
					color: '#ECEEF2'
				}
			},
			grid: {
				show: false,
				borderColor: "#ECEEF2"
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					size: 7,
					offsetX: -2,
					offsetY: -.5,
					shape: 'diamond'
				}
			}
		}
		var chart = new ApexCharts(document.querySelector("#revenue_two"), options);
		chart.render();
	}

	/* Conversion JS*/
	const getConversionId = document.getElementById('conversion');
	if (getConversionId) {
		var options = {
			series: [
				{
					name: "Conversion",
					data: [20, 40, 25, 60, 30, 50]
				},
					{
					name: "Not Conversion",
					data: [20, 20, 25, 15, 35, 25]
				}
			],
			chart: {
				type: "bar",
				height: 100,
				stacked: true,
				toolbar: {
					show: false
				},
				zoom: {
					enabled: true
				}
			},
			plotOptions: {
				bar: {
					columnWidth: "55%"
				}
			},
			colors: [
				"#605DFF", "#C2CDFF"
			],
			grid: {
				borderColor: "#ffffff"
			},
			stroke: {
				width: 2,
				show: true,
				colors: ["transparent"]
			},
			dataLabels: {
				enabled: false
			},
			xaxis: {
				categories: [
					"Jan",
					"Feb",
					"Mar",
					"Apr",
					"May",
					"Jun"
				],
				axisTicks: {
					show: false,
					color: '#B1BBC8'
				},
				axisBorder: {
					show: false,
					color: '#B1BBC8'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				show: false,
				labels: {
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				}
			},
			legend: {
				show: false,
				fontSize: '12px',
				position: 'bottom',
				horizontalAlign: 'center',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					shape: "diamond",
				}
			},
			fill: {
				opacity: 1
			}
		};
		var chart = new ApexCharts(document.querySelector("#conversion"), options);
		chart.render();
	}

	/* Active Users JS*/
	const getActiveUsersId = document.getElementById('active_users');
	if (getActiveUsersId) {
		var options = {
			series: [
				{
					name: "Active Users",
					data: [20, 15, 25, 95, 25, 65, 15, 25, 15, 20]
				},
			],
			chart: {
				type: "bar",
				height: 408,
				toolbar: {
					show: false
				}
			},
			colors: [
				"#605DFF", "#AD63F6", "#3584FC", "#FD5812"
			],
			plotOptions: {
				bar: {
					columnWidth: "15%",
					borderRadius: 5
				}
			},
			grid: {
				show: true,
				borderColor: "#ECF0FF"
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				width: 3,
				show: true,
				colors: ["transparent"]
			},
			xaxis: {
				categories: [
					"Oct 01",
					"Oct 02",
					"Oct 03",
					"Oct 04",
					"Oct 05",
					"Oct 06",
					"Oct 07",
					"Oct 08",
					"Oct 09",
					"Oct 10",
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: true,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 5,
				max: 100,
				min: 0,
				labels: {
					formatter: (val) => {
						return '$' + val + 'K'
					},
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				}
			},
			legend: {
				show: false,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'left',
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					size: 7,
					offsetX: -2,
					offsetY: -.5,
					shape: 'diamond'
				}
			},
		};
		var chart = new ApexCharts(document.querySelector("#active_users"), options);
		chart.render();
	}

	/* Product Trade Condition JS*/
	const getProductTradeConditionId = document.getElementById('product_trade_condition');
	if (getProductTradeConditionId) {
		var options = {
			series: [
				{
					name: "Price",
					data: [
						{
							x: new Date(1538778600000),
							y: [6629.81, 6650.5, 6623.04, 6633.33]
						},
						{
							x: new Date(1538780400000),
							y: [6632.01, 6643.59, 6620, 6630.11]
						},
						{
							x: new Date(1538782200000),
							y: [6630.71, 6648.95, 6623.34, 6635.65]
						},
						{
							x: new Date(1538784000000),
							y: [6635.65, 6651, 6629.67, 6638.24]
						},
						{
							x: new Date(1538785800000),
							y: [6638.24, 6640, 6620, 6624.47]
						},
						{
							x: new Date(1538787600000),
							y: [6624.53, 6636.03, 6621.68, 6624.31]
						},
						{
							x: new Date(1538789400000),
							y: [6624.61, 6632.2, 6617, 6626.02]
						},
						{
							x: new Date(1538791200000),
							y: [6627, 6627.62, 6584.22, 6603.02]
						},
						{
							x: new Date(1538793000000),
							y: [6605, 6608.03, 6598.95, 6604.01]
						},
						{
							x: new Date(1538794800000),
							y: [6604.5, 6614.4, 6602.26, 6608.02]
						},
						{
							x: new Date(1538796600000),
							y: [6608.02, 6610.68, 6601.99, 6608.91]
						},
						{
							x: new Date(1538798400000),
							y: [6608.91, 6618.99, 6608.01, 6612]
						},
						{
							x: new Date(1538800200000),
							y: [6612, 6615.13, 6605.09, 6612]
						},
						{
							x: new Date(1538802000000),
							y: [6612, 6624.12, 6608.43, 6622.95]
						},
						{
							x: new Date(1538803800000),
							y: [6623.91, 6623.91, 6615, 6615.67]
						},
						{
							x: new Date(1538805600000),
							y: [6618.69, 6618.74, 6610, 6610.4]
						},
						{
							x: new Date(1538807400000),
							y: [6611, 6622.78, 6610.4, 6614.9]
						},
						{
							x: new Date(1538809200000),
							y: [6614.9, 6626.2, 6613.33, 6623.45]
						},
						{
							x: new Date(1538811000000),
							y: [6623.48, 6627, 6618.38, 6620.35]
						},
						{
							x: new Date(1538812800000),
							y: [6619.43, 6620.35, 6610.05, 6615.53]
						},
						{
							x: new Date(1538814600000),
							y: [6615.53, 6617.93, 6610, 6615.19]
						},
						{
							x: new Date(1538816400000),
							y: [6615.19, 6621.6, 6608.2, 6620]
						},
						{
							x: new Date(1538818200000),
							y: [6619.54, 6625.17, 6614.15, 6620]
						},
						{
							x: new Date(1538820000000),
							y: [6620.33, 6634.15, 6617.24, 6624.61]
						},
						{
							x: new Date(1538821800000),
							y: [6625.95, 6626, 6611.66, 6617.58]
						},
						{
							x: new Date(1538823600000),
							y: [6619, 6625.97, 6595.27, 6598.86]
						},
						{
							x: new Date(1538825400000),
							y: [6598.86, 6598.88, 6570, 6587.16]
						},
						{
							x: new Date(1538827200000),
							y: [6588.86, 6600, 6580, 6593.4]
						},
						{
							x: new Date(1538829000000),
							y: [6593.99, 6598.89, 6585, 6587.81]
						},
						{
							x: new Date(1538830800000),
							y: [6587.81, 6592.73, 6567.14, 6578]
						},
						{
							x: new Date(1538832600000),
							y: [6578.35, 6581.72, 6567.39, 6579]
						},
						{
							x: new Date(1538834400000),
							y: [6579.38, 6580.92, 6566.77, 6575.96]
						},
						{
							x: new Date(1538836200000),
							y: [6575.96, 6589, 6571.77, 6588.92]
						},
						{
							x: new Date(1538838000000),
							y: [6588.92, 6594, 6577.55, 6589.22]
						},
						{
							x: new Date(1538839800000),
							y: [6589.3, 6598.89, 6589.1, 6596.08]
						},
						{
							x: new Date(1538841600000),
							y: [6597.5, 6600, 6588.39, 6596.25]
						},
						{
							x: new Date(1538843400000),
							y: [6598.03, 6600, 6588.73, 6595.97]
						},
						{
							x: new Date(1538845200000),
							y: [6595.97, 6602.01, 6588.17, 6602]
						},
						{
							x: new Date(1538847000000),
							y: [6602, 6607, 6596.51, 6599.95]
						},
						{
							x: new Date(1538848800000),
							y: [6600.63, 6601.21, 6590.39, 6591.02]
						},
						{
							x: new Date(1538850600000),
							y: [6591.02, 6603.08, 6591, 6591]
						},
						{
							x: new Date(1538852400000),
							y: [6591, 6601.32, 6585, 6592]
						},
						{
							x: new Date(1538854200000),
							y: [6593.13, 6596.01, 6590, 6593.34]
						},
						{
							x: new Date(1538856000000),
							y: [6593.34, 6604.76, 6582.63, 6593.86]
						},
						{
							x: new Date(1538857800000),
							y: [6593.86, 6604.28, 6586.57, 6600.01]
						},
						{
							x: new Date(1538859600000),
							y: [6601.81, 6603.21, 6592.78, 6596.25]
						},
						{
							x: new Date(1538861400000),
							y: [6596.25, 6604.2, 6590, 6602.99]
						},
						{
							x: new Date(1538863200000),
							y: [6602.99, 6606, 6584.99, 6587.81]
						},
						{
							x: new Date(1538865000000),
							y: [6587.81, 6595, 6583.27, 6591.96]
						},
						{
							x: new Date(1538866800000),
							y: [6591.97, 6596.07, 6585, 6588.39]
						},
						{
							x: new Date(1538868600000),
							y: [6587.6, 6598.21, 6587.6, 6594.27]
						},
						{
							x: new Date(1538870400000),
							y: [6596.44, 6601, 6590, 6596.55]
						},
						{
							x: new Date(1538872200000),
							y: [6598.91, 6605, 6596.61, 6600.02]
						},
						{
							x: new Date(1538874000000),
							y: [6600.55, 6605, 6589.14, 6593.01]
						},
						{
							x: new Date(1538875800000),
							y: [6593.15, 6605, 6592, 6603.06]
						},
						{
							x: new Date(1538877600000),
							y: [6603.07, 6604.5, 6599.09, 6603.89]
						},
						{
							x: new Date(1538879400000),
							y: [6604.44, 6604.44, 6600, 6603.5]
						},
						{
							x: new Date(1538881200000),
							y: [6603.5, 6603.99, 6597.5, 6603.86]
						},
						{
							x: new Date(1538883000000),
							y: [6603.85, 6605, 6600, 6604.07]
						},
						{
							x: new Date(1538884800000),
							y: [6604.98, 6606, 6604.07, 6606]
						}
					]
				}
			],
			chart: {
				type: "candlestick",
				height: 358,
				toolbar: {
					show: false
				}
			},
			plotOptions: {
				candlestick: {
					colors: {
						upward: '#FE7A36',
						downward: '#5DA8FF'
					},
					wick: {
						useFillColor: true
					}
				}
			},
			xaxis: {
				type: "datetime",
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: true,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tooltip: {
					enabled: true
				},
				labels: {
					show: true,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					},
					formatter: (val) => {
						return '$' + val + 'K'
					},
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				opposite: "right",
			},
			grid: {
				show: true,
				borderColor: "#ECEEF2"
			}
		}
		var chart = new ApexCharts(document.querySelector("#product_trade_condition"), options);
		chart.render();
	}

	/* Gross Revenue JS*/
	const getGrossRevenueId = document.getElementById('gross_revenue');
	if (getGrossRevenueId) {
		var options = {
			series: [
				{
					name: "October",
					data: [35, 50, 55, 60, 50, 60, 55, 60, 78, 40]
				},
				{
					name: "September",
					data: [70, 50, 40, 40, 62, 52, 80, 40, 60, 53]
				}
			],
			chart: {
				type: "area",
				height: 372,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false,
				}
			},
			colors: [
				"#757DFF", "#E9D5FF"
			],
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: "straight",
				width: [3, 3, 0],
				dashArray: [0, 6, 0],
			},
			grid: {
				borderColor: "#ECF0FF"
			},
			fill: {
				type: 'gradient',
				gradient: {
					stops: [0, 90, 100],
					shadeIntensity: 1,
					opacityFrom: 0,
					opacityTo: 0
				}
			},
			xaxis: {
				categories: [
					"Oct 01",
					"Oct 04",
					"Oct 08",
					"Oct 12",
					"Oct 16",
					"Oct 20",
					"Oct 24",
					"Oct 28",
					"Nov 02",
					"Nov 06",
				],
				axisTicks: {
					show: false,
					color: '#B1BBC8'
				},
				axisBorder: {
					show: false,
					color: '#B1BBC8'
				},
				labels: {
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 5,
				max: 100,
				min: 0,
				labels: {
					formatter: (val) => {
						return '$' + val / 1 + 'K'
					},
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			legend: {
				show: true,
				position: 'bottom',
				fontSize: '12px',
				horizontalAlign: 'left',
				offsetX: 10,
      			offsetY: 5,
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			},
			tooltip: {
				y: {
					formatter: function(val) {
					return "$" + val + "k";
					}
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#gross_revenue"), options);
		chart.render();
	}


	//**<---- Dashboard Real Estate ---->**//


	/* Property Revenue JS*/
	const getPropertyRevenueId = document.getElementById('property_revenue');
	if (getPropertyRevenueId) {
		var options = {
			series: [
				{
					name: 'Income',
					data: [
						{
							x: '2019',
							y: 1292,
							goals: [
								{
								name: 'Expenses',
								value: 1400,
								strokeHeight: 5,
								strokeColor: '#FFCEA9'
								}
							]
						},
						{
							x: '2018',
							y: 4432,
							goals: [
								{
									name: 'Expenses',
									value: 5400,
									strokeHeight: 5,
									strokeColor: '#FFCEA9'
								}
							]
						},
						{
							x: '2020',
							y: 5423,
							goals: [
								{
								name: 'Expenses',
								value: 5200,
								strokeHeight: 5,
								strokeColor: '#FFCEA9'
								}
							]
						},
						{
							x: '2021',
							y: 6653,
							goals: [
								{
								name: 'Expenses',
								value: 6500,
								strokeHeight: 5,
								strokeColor: '#FFCEA9'
								}
							]
						},
						{
							x: '2022',
							y: 8133,
							goals: [
								{
								name: 'Expenses',
								value: 6600,
								strokeHeight: 13,
								strokeWidth: 0,
								strokeLineCap: 'round',
								strokeColor: '#FFCEA9'
								}
							]
						},
						{
							x: '2023',
							y: 7132,
							goals: [
								{
								name: 'Expenses',
								value: 7500,
								strokeHeight: 5,
								strokeColor: '#FFCEA9'
								}
							]
						},
						{
							x: '2024',
							y: 7332,
							goals: [
								{
								name: 'Expenses',
								value: 8700,
								strokeHeight: 5,
								strokeColor: '#FFCEA9'
								}
							]
						},
					]
				}
			],
			chart: {
				height: 125,
				type: 'bar',
				toolbar: {
					show: false,
				}
			},
			plotOptions: {
				bar: {
					columnWidth: '60%'
				}
			},
			colors: ['#FD5812'],
			dataLabels: {
				enabled: false
			},
			legend: {
				show: true,
				showForSingleSeries: true,
				customLegendItems: ['Income', 'Expenses'],
				position: 'right',
				fontSize: '12px',
				horizontalAlign: 'bottom',
				offsetX: -12,
				offsetY: 51,
				itemMargin: {
					horizontal: 0,
					vertical: 4
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					shape: "diamond",
					offsetX: -1,
					fillColors: ['#FD5812', '#FFCEA9']
				},
			},
			xaxis: { 
				labels: {
					show: false,
				},
				axisTicks: {
					show: false,
				}
			},
			yaxis: { 
				labels: {
					show: false,
				}
			},
			grid: {
				show: false,
			},
			tooltip: {
				y: {
					formatter: function(val) {
					return "$" + val + "K";
					}
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#property_revenue"), options);
		chart.render();
	}

	/* New Listings vs Sold Properties JS*/
	const getNewListingsVsSoldPropertiesId = document.getElementById('new_listings_vs_sold_properties');
	if (getNewListingsVsSoldPropertiesId) {
		var options = {
			series: [
				{
					name: "New Listings",
					data: [30, 40, 20, 50, 60, 70, 50, 40, 30, 60, 50, 45]
				},
				{
					name: "Sold Properties",
					data: [80, 50, 90, 40, 50, 90, 55, 60, 80, 30, 95, 80]
				}
			],
			chart: {
				type: "area",
				height: 296,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false,
				}
			},
			colors: [
				"#605DFF", "#37D80A"
			],
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: "straight",
				width: [2, 2],
				dashArray: [6, 6],
			},
			grid: {
				borderColor: '#ECF0FF', 
				strokeDashArray: 4,
				xaxis: {
					lines: {
						show: false,
					}
				},
			},
			fill: {
				type: 'gradient',
				gradient: {
					stops: [0, 90, 100],
					shadeIntensity: 1,
					opacityFrom: 0,
					opacityTo: 0.5
				}
			},
			xaxis: {
				categories: [
					"Jan",
					"Feb",
					"Mar",
					"Apr",
					"May",
					"Jun",
					"Jul",
					"Aug",
					"Sep",
					"Oct",
					"Nov",
					"Dec"
				],
				axisTicks: {
					show: false,
					color: '#B1BBC8'
				},
				axisBorder: {
					show: false,
					color: '#B1BBC8'
				},
				labels: {
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 5,
				max: 100,
				min: 0,
				labels: {
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			legend: {
				show: true,
				position: 'bottom',
				fontSize: '12px',
				horizontalAlign: 'center',
				fontFamily: "Inter",
				fontWeight: 400,
				offsetY: 10,
				itemMargin: {
					horizontal: 8,
					vertical: 10
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			},
			tooltip: {
				y: {
					formatter: function(val) {
						return "" + val + "k";
					}
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#new_listings_vs_sold_properties"), options);
		chart.render();
	}

	/* Properties For Sale JS*/
	const getPropertiesForSaleId = document.getElementById('properties_for_sale');
	if (getPropertiesForSaleId) {
		var options = {
			chart: {
				height: 170,
				width: 170,
				type: "radialBar",
			},
	
			series: [75],
			colors: ["#37D80A"],
			plotOptions: {
				radialBar: {
					hollow: {
						margin: 0,
						size: "35%",
						background: "#F5F7F8",
						margin: 10,
					},
					track: {
						background: "#F5F7F8"
					},
					dataLabels: {
						name: {
							offsetY: -10,
							color: "#4b9bfa",
							fontSize: ".625rem",
							show: false
						},
						value: {
							show: true,
							offsetY: 5,
							color: "#3A4252",
							fontSize: "14px",
							fontWeight: 700,
							fontFamily: "Inter",
						}
					}
				}
			},
			stroke: {
				lineCap: "0"
			},
			labels: ["Status"]
		};
		document.querySelector("#properties_for_sale").innerHTML = ""
		var chart = new ApexCharts(document.querySelector("#properties_for_sale"), options);
		chart.render();
	}

	/* Properties For Rent JS*/
	const getPropertiesForRentId = document.getElementById('properties_for_rent');
	if (getPropertiesForRentId) {
		var options = {
			chart: {
				height: 170,
				width: 170,
				type: "radialBar",
			},
	
			series: [35],
			colors: ["#605DFF"],
			plotOptions: {
				radialBar: {
					hollow: {
						margin: 0,
						size: "35%",
						background: "#F5F7F8",
						margin: 10,
					},
					track: {
						background: "#F5F7F8"
					},
					dataLabels: {
						name: {
							offsetY: -10,
							color: "#4b9bfa",
							fontSize: ".625rem",
							show: false
						},
						value: {
							show: true,
							offsetY: 5,
							color: "#3A4252",
							fontSize: "14px",
							fontWeight: 700,
							fontFamily: "Inter",
						}
					}
				}
			},
			stroke: {
				lineCap: "0"
			},
			labels: ["Status"]
		};
		document.querySelector("#properties_for_rent").innerHTML = ""
		var chart = new ApexCharts(document.querySelector("#properties_for_rent"), options);
		chart.render();
	}

	/* Rental Income JS*/
	const getRentalIncomeId = document.getElementById('rental_income');
	if (getRentalIncomeId) {
		var options = {
			series: [
				{
					name: "Time Spent",
					data: [400, 500, 300, 650, 220, 400, 380, 750, 350, 250, 200, 100,]
				}
			],
			chart: {
				type: "bar",
				height: 255,
				toolbar: {
					show: false
				}
			},
			colors: [
				"#605DFF"
			],
			plotOptions: {
				bar: {
					columnWidth: "55%"
				}
			},
			grid: {
				borderColor: '#ECF0FF', 
				strokeDashArray: 4,
				xaxis: {
					lines: {
						show: false,
					}
				},
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				width: 4,
				show: true,
				colors: ["transparent"]
			},
			xaxis: {
				categories: [
					"Jan",
					"Feb",
					"Mar",
					"Apr",
					"May",
					"Jun",
					"Jul",
					"Aug",
					"Sep",
					"Oct",
					"Nov",
					"Dec",
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: true,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 4,
				min: 0,
				labels: {
					formatter: (val) => {
						return '$' + val / 1 + 'K'
					},
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				}
			},
			tooltip: {
				y: {
					formatter: function(val) {
						return val + " hours";
					}
				}
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'left',
				fontFamily: "Inter",
				fontWeight: 400,
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			},
			tooltip: {
				y: {
					formatter: function(val) {
						return "" + val + "k";
					}
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#rental_income"), options);
		chart.render();
	}
	
	/* Social Search JS*/
	const getSocialSearchId = document.getElementById('social_search');
	if (getSocialSearchId) {
		var options = {
			series: [90, 80, 70, 60],
			chart: {
				height: 230,
				type: 'radialBar',
			},
			plotOptions: {
				radialBar: {
					dataLabels: {
						name: {
							fontSize: '14px',
						},
						value: {
							fontSize: '14px',
						},
						total: {
							show: true,
							label: 'Total',
							formatter: function (w) {
								// By default this function returns the average of all series. The below is just an example to show the use of custom formatter function
								return 249
							}
						},
					},
					hollow: {
						margin: 10,
						size: '40%',
						background: 'transparent',
					},
				}
			},
			labels: ['LinkedIn', 'Twitter', 'Facebook', 'Instagram'],
			colors: ['#AD63F6', '#3584FC', '#37D80A', '#FD5812']
		};
		var chart = new ApexCharts(document.querySelector("#social_search"), options);
		chart.render();
	}


	//**<---- Dashboard Shipment ---->**//


	/* Shipment Delivered JS */
	const getShipmentDeliveredId = document.getElementById('shipment_delivered');
	if (getShipmentDeliveredId) {

		var options = {
			series: [{
				name: 'Ship Cargo',
				type: 'column',
				data: [23, 11, 22, 27, 13, 22, 37, 21, 44, 22, 30]
		  	}, {
				name: 'Truck Cargo',
				type: 'area',
				data: [44, 55, 41, 67, 22, 43, 21, 41, 56, 27, 43]
		  	}, {
				name: 'Car Box',
				type: 'line',
				data: [30, 25, 36, 30, 45, 35, 64, 52, 59, 36, 39]
		  	}],
			chart: {
				height: 300,
				type: 'line',
				stacked: false,
				toolbar: {
					show: false
				}
		  	},
			stroke: {
				width: [0, 2, 5],
				curve: 'smooth'
			},
			plotOptions: {
				bar: {
					columnWidth: '50%',
				},
			},
			//colors: ['red', 'blue', 'yellow'],
			fill: {
				opacity: [0.85, 0.25, 1],
				gradient: {
					inverseColors: false,
					shade: 'light',
					type: "vertical",
					opacityFrom: 0.85,
					opacityTo: 0.55,
					stops: [0, 100, 100, 100]
				}
			},
			markers: {
				size: 0
			},
			grid: {
				borderColor: '#ECF0FF', 
				strokeDashArray: 4,
				xaxis: {
					lines: {
						show: false,
					}
				},
			},
			xaxis: {
				categories: [
					"Jan",
					"Feb",
					"Mar",
					"Apr",
					"May",
					"Jun",
					"Jul",
					"Aug",
					"Sep",
					"Oct",
					"Nov",
				],
				axisTicks: {
					show: false,
					color: '#B1BBC8'
				},
				axisBorder: {
					show: false,
					color: '#B1BBC8'
				},
				labels: {
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 5,
				max: 100,
				min: 0,
				labels: {
					formatter: (val) => {
						return '' + val / 1 + '%'
					},
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			legend: {
				show: true,
				position: 'bottom',
				fontSize: '12px',
				horizontalAlign: 'center',
				fontFamily: "Inter",
				fontWeight: 400,
				offsetY: 10,
				itemMargin: {
					horizontal: 8,
					vertical: 10
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			},
			tooltip: {
				y: {
					formatter: function(val) {
					return "" + val + "%";
					}
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#shipment_delivered"), options);
		chart.render();

	}

	/* Average Delivery Time JS */
	const getAverageDeliveryTimeId = document.getElementById('average_delivery_time');
	if (getAverageDeliveryTimeId) {
		var options = {
			series: [
				{
					name: "Average Delivery Time",
					data: [30, 40, 50, 60, 70, 80, 95, 95]
				}
			],
			chart: {
				type: "bar",
				height: 200,
				toolbar: {
					show: false
				}
			},
			colors: [
				"#BDDCFF"
			],
			plotOptions: {
				bar: {
					columnWidth: "55%",
				}
			},
			fill: {
				opacity: [0.85, 0.25, 1],
				gradient: {
					inverseColors: false,
					shade: 'light',
					type: "vertical",
					opacityFrom: 0.85,
					opacityTo: 0.55,
					stops: [0, 100, 100, 100]
				}
			},
			grid: {
				show: true,
				borderColor: "#ECEEF2"
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				width: 0,
				show: true,
				colors: ["transparent"]
			},
			xaxis: {
				categories: [
					"Jan",
					"Feb",
					"Mar",
					"Apr",
					"May",
					"Jun",
					"Jul",
					"Aug"
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: true,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 4,
				max: 100,
				min: 0,
				labels: {
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					},
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
			},
			tooltip: {
				y: {
					formatter: (val) => {
						return '' + val + 'Hours'
					},
				}
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'left',
				fontFamily: "Inter",
				fontWeight: 400,
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			},
			states: {
				hover: {
					filter: {
						type: 'darken',
						value: 0.1,
					}
				},
			}
		};
		var chart = new ApexCharts(document.querySelector("#average_delivery_time"), options);
		chart.render();
	}

	/* Live Shipment Status JS*/
	const getLiveShipmentStatusId = document.getElementById('live_shipment_status');
	if (getLiveShipmentStatusId) {
		var options = {
			series: [
				{
					name: "In Transit",
					data: [70, 60, 40, 35, 30, 30, 40]
				},
				{
					name: "Delivered",
					data: [40, 45, 50, 55, 60, 65, 65]
				},
				{
					name: "Delayed",
					data: [15, 20, 25, 30, 25, 20, 15]
				},
				{
					name: "Canceled",
					data: [5, 10, 15, 20, 15, 10, 5]
				}
			],
			chart: {
				height: 200,
				type: "line",
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			dataLabels: {
				enabled: false
			},
			colors: [
				"#3584FC", "#37D80A", "#FD5812", "#EE3E08"
			],
			stroke: {
				curve: "straight",
				width: 2
			},
			grid: {
				borderColor: '#EEF1FF',
				xaxis: {
					lines: {
						show: true
					}
				},   
				yaxis: {
					lines: {
						show: true
					}
				},
			},
			markers: {
				size: 3,
				strokeWidth: 0,
				shape: ["circle", "circle"],
				strokeDashArray: 0,
				strokeWidth: 2,
				hover: {
					size: 4
				}
			},
			xaxis: {
				categories: [
					"D1",
					"D2",
					"D3",
					"D4",
					"D5",
					"D6",
					"D7"
				],
				axisTicks: {
					show: false,
					color: '#B1BBC8'
				},
				axisBorder: {
					show: false,
					color: '#B1BBC8'
				},
				labels: {
					show: true,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 6,
				min: 0,
				labels: {
					style: {
						colors: "#8695AA", 
						fontSize: "12px"
					}
				}
			},
			legend: {
				show: true,
				position: 'left',
				fontSize: '12px',
				horizontalAlign: 'bottom',
				offsetX: -12,
				offsetY: 0,
				fontFamily: "Inter",
				fontWeight: 400,
				itemMargin: {
					horizontal: 0,
					vertical: 4
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					shape: "diamond",
					offsetX: -1,
				},
			},
		};
		var chart = new ApexCharts(document.querySelector("#live_shipment_status"), options);
		chart.render();
	}

	/* Top Shipping Methods JS */
	const getTopShippingMethodsId = document.getElementById('top_shipping_methods');
	if (getTopShippingMethodsId) {
		var options = {

			series: [
				45, 30, 15, 10
			],
			chart: {
				height: 180,
				type: "pie",
			},
			labels: [
				"Air", "Road", "Sea", "Rail"
			],
			colors: [
				"#3584FC", "#FD5812", "#605DFF", "#37D80A"
			],
			dataLabels: {
				enabled: true,
				style: {
					fontSize: '10px',
					fontFamily: 'Inter',
					colors: ['#fff']
				},
				dropShadow: {
					enabled: true,
					top: 0,
					left: 0,
					blur: 0,
					color: '#000',
					opacity: 0
				}
			},
			plotOptions: {
				pie: {
					expandOnClick: false
				}
			},
			stroke: {
				width: 1,
				show: true,
				colors: ["#ffffff"]
			},
			legend: {
				show: true,
				position: 'right',
				fontSize: '12px',
				horizontalAlign: 'bottom',
				offsetX: -12,
				offsetY: 0,
				fontFamily: "Inter",
				fontWeight: 400,
				itemMargin: {
					horizontal: 0,
					vertical: 4
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					shape: "diamond",
					offsetX: -1,
				},
			},
		};
		var chart = new ApexCharts(document.querySelector("#top_shipping_methods"), options);
		chart.render();
	}

	/* Todays Shipments JS */
	const getTodaysShipmentsId = document.getElementById('todays_shipments');
	if (getTodaysShipmentsId) {
		var options = {
			series: [{
				name: "Todays Shipments",
				data: [10, 31, 25, 40, 50, 50, 100]
			}],
			chart: {
				height: 175,
				type: 'line',
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				},
			},
			markers: {
				size: 0,
				strokeWidth: 0,
				hover: {
				  size: 0
				}
			},
			colors: ['#5C61F2'],
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: 'straight',
				width: 2,
			},
			grid: {
				borderColor: '#ECF0FF',
				row: {
					colors: ['#F6F7F9', 'transparent'], // takes an array which will be repeated on columns
					opacity: 0.5,
				},
			},
			xaxis: {
				categories: [
					"3am",
					"6am",
					"9am",
					"12pm",
					"3pm",
					"6pm",
					"9pm",
					"12am",
				],
				axisTicks: {
					show: false,
					color: '#B1BBC8'
				},
				axisBorder: {
					show: false,
					color: '#B1BBC8'
				},
				labels: {
					show: true,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 3,
				labels: {
					style: {
						colors: "#8695AA", 
						fontSize: "12px"
					}
				}
			},
			tooltip: {
				y: {
					formatter: (val) => {
						return '' + val + 'Ton'
					},
				}
			},
		};
		var chart = new ApexCharts(document.querySelector("#todays_shipments"), options);
		chart.render();
	}

	/* On-Time Delivery JS */
	const getOnTimeDeliveryId = document.getElementById('on_time_delivery');
	if (getOnTimeDeliveryId) {
		var options = {

			series: [
				85, 15,
			],
			chart: {
				height: 180,
				type: "pie",
			},
			labels: [
				"Delivered", "Canceled"
			],
			colors: [
				"#37D80A", "#FF4023"
			],
			dataLabels: {
				enabled: true,
				style: {
					fontSize: '10px',
					fontFamily: 'Inter',
					colors: ['#fff']
				},
				dropShadow: {
					enabled: true,
					top: 0,
					left: 0,
					blur: 0,
					color: '#000',
					opacity: 0
				}
			},
			plotOptions: {
				pie: {
					expandOnClick: false
				}
			},
			stroke: {
				width: 1,
				show: true,
				colors: ["#ffffff"]
			},
			legend: {
				show: true,
				position: 'left',
				fontSize: '12px',
				horizontalAlign: 'bottom',
				offsetX: -25,
				offsetY: 109,
				fontFamily: "Inter",
				fontWeight: 400,
				itemMargin: {
					horizontal: 0,
					vertical: 4
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					shape: "diamond",
					offsetX: -1,
				},
			},
		};
		var chart = new ApexCharts(document.querySelector("#on_time_delivery"), options);
		chart.render();
	}


	//**<---- Dashboard Finance ---->**//


	/* Project Static JS */
	const getStaticId = document.getElementById('static');
	if (getStaticId) {
		var options = {

			series: [
				{
					name: "Income",
					data: [40, 60, 50, 40, 45, 38, 42, 35, 45,]
				},
				{
					name: "Expenses",
					data: [60, 80, 90, 80, 60, 70, 60, 90, 60,]
				},
			],
			chart: {
				type: "bar",
				height: 360,
				toolbar: {
					show: false
				}
			},
			colors: [
				"#9CAAFF", "#605DFF"
			],
			plotOptions: {
				bar: {
					columnWidth: "60%"
				}
			},
			grid: {
				show: true,
				borderColor: "#ECEEF2"
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				width: 4,
				show: true,
				colors: ["transparent"]
			},
			xaxis: {
				categories: [
					"Mon",
					"Tue",
					"Wed",
					"Thu",
					"Fri",
					"Sat",
					"Sun"
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: true,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 6,
				min: 0,
				labels: {
					formatter: (val) => {
					    return '$' + val + ''
					},
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				}
			},
			tooltip: {
				y: {
					formatter: function(val) {
						return "$" + val + "";
					}
				}
			},
			legend: {
				show: true,
				position: 'bottom',
				fontSize: '12px',
				horizontalAlign: 'center',
				fontFamily: 'Inter',
				offsetY: 10,
				fontFamily: "Inter",
				fontWeight: 400,
				itemMargin: {
					horizontal: 8,
					vertical: 10
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			},
		};
		var chart = new ApexCharts(document.querySelector("#static"), options);
		chart.render();
	}

	/* Weekly Expenses JS*/
	const getWeeklyExpensesId = document.getElementById('weekly_expenses');
	if (getWeeklyExpensesId) {
		var options = {
			series: [
				{
					name: "Weekly Expenses",
					data: [0, 25, 40, 50, 80, 70, 140]
				},
			],
			chart: {
				type: "area",
				height: 130,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			colors: [
				"#AD63F6", "#605DFF"
			],
			dataLabels: {
				enabled: false
			},
			grid: {
				borderColor: "#ECEEF2"
			},
			stroke: {
				curve: "straight", //straight', 'smooth', 'monotoneCubic', 'stepline
				width: 2
			},
			xaxis: {
				categories: [
					"Mon",
					"Tue",
					"Wed",
					"Thu",
					"Fri",
					"Sat",
					"Sun",
				],
				axisTicks: {
					show: false,
					color: '#B1BBC8'
				},
				axisBorder: {
					show: false,
					color: '#B1BBC8'
				},
				labels: {
					show: true,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 4,
				max: 140,
				min: 0,
				labels: {
					formatter: (val) => {
						return '$' + val + ''
					},
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				},
				opposite: "right",
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'left',
				fontFamily: "Inter",
				fontWeight: 400,
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#weekly_expenses"), options);
		chart.render();
	}

	// Net Profit JS
	const getNetProfitId = document.getElementById('net_profit');
	if (getNetProfitId) {
		var options = {
			series: [
				{
					name: "Net Profit",
					data: [30, 70, 80, 95, 90, 20, 40]
				}
			],
			chart: {
				type: "bar",
				height: 168,
				toolbar: {
					show: false
				}
			},
			colors: [
				"#37D80A"
			],
			plotOptions: {
				bar: {
					columnWidth: "30%",
					rangeBarOverlap: false,
					isFunnel3d: false,
					colors: {
						ranges: [{
							from: 0,
							to: 0,
							color: undefined
						}],
						backgroundBarColors: ["#37D80A"],
						backgroundBarOpacity: 0.2,
						backgroundBarRadius: 0,
					},
				}
			},
			fill: {
				opacity: [0.85, 0.25, 1],
				gradient: {
					inverseColors: false,
					shade: 'light',
					type: "vertical",
					opacityFrom: 0.85,
					opacityTo: 0.55,
					stops: [0, 100, 100, 100]
				}
			},
			grid: {
				show: true,
				borderColor: "#ECEEF2"
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				width: 0,
				show: true,
				colors: ["transparent"]
			},
			xaxis: {
				categories: [
					"Mon",
					"Tue",
					"Wed",
					"Thu",
					"Fri",
					"Sat",
					"Sun"
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: true,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 4,
				max: 100,
				min: 0,
				labels: {
					style: {
						colors: "#64748B",
						fontSize: "12px"
					},
					formatter: (val) => {
						return '$' + val + ''
					},
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
			},
			tooltip: {
				y: {
					formatter: (val) => {
						return '$' + val + 'K'
					},
				}
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'left',
				fontFamily: "Inter",
				fontWeight: 400,
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#net_profit"), options);
		chart.render();
	}


	//**<---- Dashboard HRM ---->**//


	/* Total Employees JS*/
	const getTotalEmployeesId = document.getElementById('total_employees');
	if (getTotalEmployeesId) {
		var options = { 
			series: [
				{
					name: "Employees",
					data: [3, 12, 8, 15, 8, 10, 15]
				}
			],
			chart: {
				type: "area",
				height: 140,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			colors: [
				"#4936F5"
			],
			fill: {
				type: "gradient",
				gradient: {
				  shadeIntensity: 1,
				  opacityFrom: 1,
				  opacityTo: 1,
				  stops: [0, 90, 100]
				}
			  },
			dataLabels: {
				enabled: false
			},
			grid: {
				show: false,
				borderColor: "#ECEEF2"
			},
			stroke: {
				curve: "smooth",
				width: 0
			},
			xaxis: {
				categories: [
					"Mon",
					"Tue",
					"Wed",
					"Thu",
					"Fri",
					"Sat",
					"Sun"
				],
				axisTicks: {
					show: false,
					color: '#B1BBC8'
				},
				axisBorder: {
					show: false,
					color: '#B1BBC8'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				// tickAmount: 6,
				show: false,
				// max: 150,
				// min: 0,
				labels: {
					formatter: (val) => {
						return val + '%'
					},
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				}
			},
			legend: {
				show: false,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'left',
				fontFamily: "Inter",
				fontWeight: 400,
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			},
			tooltip: {
				y: {
					formatter: function(val) {
						return "Total:" + val;
					}
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#total_employees"), options);
		chart.render();
	}

	/* Resigned Employees JS*/ 
	const getResignedEmployeesId = document.getElementById('resigned_employees');
	if (getResignedEmployeesId) {
		var options = { 
			series: [
				{
					name: "Resigned",
					data: [44, 30, 57, 35, 61, 35, 63]
				}
			],
			chart: {
				type: "bar",
				height: 140,
				toolbar: {
					show: false
				}
			},
			plotOptions: {
				bar: {
					columnWidth: "50%",
					borderRadius: 2
				}
			},
			colors: [
				"#EE3E08"
			],
			dataLabels: {
				enabled: false
			},
			grid: {
				show: false,
				borderColor: "#ECEEF2"
			},
			stroke: {
				width: 2,
				show: true,
				colors: ["transparent"]
			},
			xaxis: {
				categories: [
					"Mon",
					"Tue",
					"Wed",
					"Thu",
					"Fri",
					"Sat",
					"Sun"
				],
				axisTicks: {
					show: false,
					color: '#B1BBC8'
				},
				axisBorder: {
					show: false,
					color: '#B1BBC8'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				// tickAmount: 6,
				show: false,
				// max: 150,
				// min: 0,
				labels: {
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				}
			},
			tooltip: {
				y: {
					formatter: function(val) {
						return "Total:" + val;
					}
				}
			},
			fill: {
				opacity: 1,
				colors: '#EE3E08',
				type: 'solid',
			}
		};
		var chart = new ApexCharts(document.querySelector("#resigned_employees"), options);
		chart.render();
	}

	/* New Employees JS*/
	const getNewEmployeesId = document.getElementById('new_employees');
	if (getNewEmployeesId) {
		var options = {
			chart: {
				height: 140,
				width: 140,
				type: "radialBar",
			},
	
			series: [30],
			colors: ["#9135E8"],
			plotOptions: {
				radialBar: {
					hollow: {
						margin: 0,
						size: "50%",
						background: "#fff",
						margin: 10,
					},
					track: {
						background: "#EDEEF0"
					},
					dataLabels: {
						name: {
							offsetY: -10,
							color: "#4b9bfa",
							fontSize: ".625rem",
							show: false
						},
						value: {
							show: true,
							offsetY: 5,
							color: "#9135E8",
							fontSize: "14px",
							fontWeight: 500,
							fontFamily: "Inter",
						}
					}
				}
			},
			stroke: {
				lineCap: 'round'
			},
			labels: ["Status"]
		};
		document.querySelector("#new_employees").innerHTML = ""
		var chart = new ApexCharts(document.querySelector("#new_employees"), options);
		chart.render();
	}

	/* Employee Attendance Trends JS*/
	const getEmployeeAttendanceTrendsId = document.getElementById('employee_attendance_trends');
	if (getEmployeeAttendanceTrendsId) {
		var options = {
			series: [
				{
					name: "Attendance",
					data: [170, 420, 300, 550, 550, 650, 820]
				},
				{
					name: "Absent",
					data: [320, 300, 650, 400, 750, 650, 600]
				}
			],
			chart: {
				type: "area",
				height: 388,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			colors: [
				"#4936F5", "#EC1F00"
			],
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: "straight", //'straight', 'smooth', 'monotoneCubic', 'stepline'
				width: [2, 2]
			},
			grid: {
				show: true,
				borderColor: "#ECEEF2",
				strokeDashArray: 10,
				xaxis: {
					lines: {
						show: false
					}
				}
			},
			fill: {
				type: 'gradient',
				gradient: {
					stops: [0, 90, 100],
					shadeIntensity: 1,
					opacityFrom: 0,
					opacityTo: 0.5
				}
			},
			xaxis: {
				categories: [
					"Sun",
					"Mon",
					"Tue",
					"Wed",
					"Thu",
					"Fri",
					"Sat"
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: true,
					color: '#D5D9E2'
				},
				labels: {
					show: true,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 5,
				max: 1000,
				min: 0,
				labels: {
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				},
				axisBorder: {
					show: true,
					color: '#D5D9E2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				}
			},
			legend: {
				show: true,
				position: 'bottom',
				fontSize: '12px',
				horizontalAlign: 'center',
				fontFamily: "Inter",
				fontWeight: 400,
				offsetY: 10,
				itemMargin: {
					horizontal: 8,
					vertical: 10
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			},
		}
		var chart = new ApexCharts(document.querySelector("#employee_attendance_trends"), options);
		chart.render();
	}

	/* Employee Work Format JS*/
	const getEmployeeWorkFormatId = document.getElementById('employee_work_format');
	if (getEmployeeWorkFormatId) {
		var options = {
			series: [120, 160, 50, 20],
			chart: {
				height: 255,
				type: "donut"
			},
			labels: [
				"Remote", "In-office", "Hybrid", "Shift"
			],
			colors: [
				"#FD5812", "#605DFF", "#37D80A", "#AD63F6"
			],
			stroke: {
				width: 1,
				show: true,
				colors: ["#ffffff"]
			},
			legend: {
				show: false,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				fontFamily: "Inter",
				fontWeight: 400,
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					shape: 'diamond',
				}
			},
			plotOptions: {
				pie: {
					expandOnClick: false,
					donut: {
						labels: {
							show: true,
							name: {
								color: '#64748B'
							},
							value: {
								show: true,
								color: '#64748B',
								fontSize: '16px',
								fontWeight: '600',
								offsetY: -6,
							},
							total: {
								show: true,
								color: '#64748B',
								fontSize: '14px',
								fontWeight: '400'
							}
						}
					}
				}
			},
			dataLabels: {
				enabled: false
			},
			tooltip: {
				enabled: false
			}
		}
		var chart = new ApexCharts(document.querySelector("#employee_work_format"), options);
		chart.render();
	}

	/* Employee Salary JS */
	const getEmployeeSalaryId = document.getElementById('employee_salary');
	if (getEmployeeSalaryId) {
		var options = {
			series: [
				{
					name: "Employee Salary",
					data: [70, 60, 80, 100, 70, 40, 80]
				}
			],
			chart: {
				type: "bar",
				height: 274,
				toolbar: {
					show: false
				}
			},
			colors: [
				"#E9D5FF"
			],
			plotOptions: {
				bar: {
					columnWidth: "52%",
					borderRadius: 4,
				}
			},
			fill: {
				opacity: [0.85, 0.25, 1],
				gradient: {
					inverseColors: false,
					shade: 'light',
					type: "vertical",
					opacityFrom: 0.85,
					opacityTo: 0.55,
					stops: [0, 100, 100, 100]
				}
			},
			grid: {
				show: false,
				borderColor: "#ECEEF2"
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				width: 0,
				show: true,
				colors: ["transparent"]
			},
			xaxis: {
				categories: [
					"Jan",
					"Feb",
					"Mar",
					"Apr",
					"May",
					"Jun",
					"Jul",
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: true,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				show: false,
				tickAmount: 4,
				labels: {
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					},
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
			},
			tooltip: {
				y: {
					formatter: (val) => {
						return '$' + val + 'K'
					},
				}
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'left',
				fontFamily: "Inter",
				fontWeight: 400,
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			},
			states: {
				hover: {
					filter: {
						type: 'darken',
						value: 0.1,
					}
				},
			}
		};
		var chart = new ApexCharts(document.querySelector("#employee_salary"), options);
		chart.render();
	}


	//**<---- Dashboard School ---->**//


	/* Attendance Analytics JS*/
	const getAttendanceAnalyticsId = document.getElementById('attendance_analytics');
	if (getAttendanceAnalyticsId) {
		var options = {
			series: [
				{
					name: "Teachers",
					data: [500, 600, 250, 600, 200, 500, 600, 120, 250, 500, 200, 250]
				},
				{
					name: "Boys",
					data: [200, 300, 200, 400, 200, 250, 350, 120, 250, 300, 120, 200]
				},
				{
					name: "Girls",
					data: [150, 250, 200, 300, 300, 150, 200, 300, 200, 250, 400, 200]
				},
			],
			chart: {
				type: "bar",
				height: 347,
				stacked: true,
				toolbar: {
					show: false
				},
				zoom: {
					enabled: true
				}
			},
			plotOptions: {
				bar: {
					borderRadius: 10,
					horizontal: false,
					columnWidth: '20px',
					borderRadiusApplication: 'end'
				}
			},
			dataLabels: {
				enabled: false
			},
			colors: [
				"#605DFF", "#9CAAFF", "#DDE4FF"
			],
			xaxis: {
				categories: [
					"Jan",
					"Feb",
					"Mar",
					"Apr",
					"May",
					"Jun",
					"Jul",
					"Aug",
					"Sep",
					"Oct",
					"Nov",
					"Dec"
				],
				axisTicks: {
					show: false,
					color: '#8695AA'
				},
				axisBorder: {
					show: true,
					color: '#D5D9E2'
				},
				labels: {
					show: true,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 5,
				max: 1450,
				min: 0,
				labels: {
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				},
				axisBorder: {
					show: true,
					color: '#D5D9E2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				}
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'left',
				fontFamily: "Inter",
				fontWeight: 400,
				offsetY: -1,
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					size: 7,
					offsetX: -2,
					offsetY: -.5,
					shape: 'diamond'
				}
			},
			fill: {
				type: 'gradient',
				opacity: 1,
				gradient: {
				  shade: '#605DFF',
				  type: "vertical",
				  shadeIntensity: 0.5,
				  gradientToColors: undefined, 
				  inverseColors: true,
				  opacityFrom: 1,
				  opacityTo: 1,
				  stops: [0, 50, 10],
				  colorStops: []
				}
			},
			grid: {
				show: true,
				strokeDashArray: 8,
				borderColor: "#ECF0FF"
			}
		}
		var chart = new ApexCharts(document.querySelector("#attendance_analytics"), options);
		chart.render();
	}

	/* Students Overview JS*/
	const getStudentsOverviewId = document.getElementById('students_overview');
	if (getStudentsOverviewId) {
		var options = {
			series: [
				{
					name: "Boys",
					data: [70, 42, 70, 120, 40, 70, 90],
				},
				{
					name: "Girls",
					data: [-70, -44, -70, -120, -40, -70, -90],
				},
			],
			colors: ['#3584FC', '#FD5812'],
			chart: {
				type: 'bar',
				height: 291,
				stacked: true,
				toolbar: {
					show: false,
				}
			},
			plotOptions: {
				bar: {
					columnWidth: '20%',
					borderRadius: 5,
					borderRadiusApplication: "end",
					borderRadiusWhenStacked: "all",
				},
			},
			  dataLabels: {
				enabled: false,
			},
			grid: {
				borderColor: '#EDEFF5', 
				strokeDashArray: 8,
				xaxis: {
					lines: {
						show: true
					}
				},
				yaxis: {
					lines: {
						show: false
					}
				},
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'left',
				fontFamily: "Inter",
				fontWeight: 400,
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					size: 7,
					offsetX: -2,
					offsetY: -.5,
					shape: 'diamond'
				}
			},
			xaxis: {
				categories: [
					"Sun",
					"Mon",
					"Tue",
					"Wed",
					"Thu",
					"Fri",
					"Sat",
				],
				axisTicks: {
					show: false,
					color: '#8695AA'
				},
				axisBorder: {
					show: false,
					color: '#D5D9E2'
				},
				labels: {
					show: true,
					style: {
						colors: "#64748B",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				show: false,
			},
			fill: {
				opacity: 1
			},
			tooltip: {
				y: {
					formatter: function (val) {
						return "Status " + val + ""
					}
				}
			},
		};
		var chart = new ApexCharts(document.querySelector("#students_overview"), options);
		chart.render();
	}

	/* New Admissions JS*/
	const getNewAdmissionsId = document.getElementById('new_admission');
	if (getNewAdmissionsId) {
		var options = {
			series: [455, 375, 220, 180, 45],
			chart: {
				height: 251,
				type: "donut"
			},
			labels: [
				"Mathematics", "English", "History", "Art", "Music"
			],
			colors: [
				"#37D80A", "#FF4023", "#605DFF", "#AD63F6", "#90C7FF"
			],
			stroke: {
				width: 1,
				show: true,
				colors: ["#ffffff"],
				lineCap: 'round'
			},
			legend: {
				show: false,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				fontFamily: "Inter",
				fontWeight: 400,
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					shape: 'diamond',
				}
			},
			plotOptions: {
				pie: {
					expandOnClick: false,
					donut: {
						size: '80%',
						labels: {
							show: true,
							name: {
								color: '#64748B'
							},
							value: {
								show: true,
								color: '#64748B',
								fontSize: '24px',
								fontWeight: '600',
								offsetY: 1,
							},
							total: {
								show: true,
								color: '#64748B',
								fontSize: '14px',
								fontWeight: '400',
								
							}
						}
					}
				}
			},
			dataLabels: {
				enabled: false
			},
			tooltip: {
				enabled: true
			}
		}
		var chart = new ApexCharts(document.querySelector("#new_admission"), options);
		chart.render();
	}
	

	//**<---- Dashboard Call Center ---->**//


	/* Total Calls JS*/
	const getTotalCallsId = document.getElementById('total_calls');
	if (getTotalCallsId) {
		var options = {
			series: [
				{
					name: "Total Calls",
					data: [200, 150, 300, 250, 500, 400, 700, 600, 900, 800, 1200, 1300]
				},
			],
			chart: {
				type: "area",
				height: 325,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			colors: [
				"#605DFF", "#DDE4FF"
			],
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: "smooth",
				width: [2, 2, 0],
				dashArray: [0, 6, 0],
			},
			grid: {
				borderColor: "#ffffff"
			},
			fill: {
				type: 'gradient',
				gradient: {
					stops: [0, 90, 100],
					shadeIntensity: 1,
					opacityFrom: 0,
					opacityTo: 0.5
				}
			},
			xaxis: {
				categories: [
					"Jan",
					"Feb",
					"Mar",
					"Apr",
					"May",
					"Jun",
					"Jul",
					"Aug",
					"Sep",
					"Oct",
					"Nov",
					"Dec"
				],
				axisTicks: {
					show: false,
					color: '#B1BBC8'
				},
				axisBorder: {
					show: false,
					color: '#B1BBC8'
				},
				labels: {
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 5,
				max: 1800,
				min: 0,
				labels: {
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'left',
				fontFamily: "Inter",
				fontWeight: 400,
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			},
		};
		var chart = new ApexCharts(document.querySelector("#total_calls"), options);
		chart.render();
	}

	/* Answered Calls JS*/
	const getAnsweredCallsId = document.getElementById('answered_calls');
	if (getAnsweredCallsId) {
		var options = {
			series: [
				{
					name: "Total Calls",
					data: [800, 150, 700, 250, 500, 400, 700, 300, 900, 800, 1200, 600]
				},
			],
			chart: {
				type: "area",
				height: 325,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			colors: [
				"#9135E8", "#9135E8"
			],
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: "smooth",
				width: [2, 2, 0],
				dashArray: [0, 6, 0],
			},
			grid: {
				borderColor: "#ffffff"
			},
			fill: {
				type: 'gradient',
				gradient: {
					stops: [0, 90, 100],
					shadeIntensity: 1,
					opacityFrom: 0,
					opacityTo: 0.5
				}
			},
			xaxis: {
				categories: [
					"Jan",
					"Feb",
					"Mar",
					"Apr",
					"May",
					"Jun",
					"Jul",
					"Aug",
					"Sep",
					"Oct",
					"Nov",
					"Dec"
				],
				axisTicks: {
					show: false,
					color: '#B1BBC8'
				},
				axisBorder: {
					show: false,
					color: '#B1BBC8'
				},
				labels: {
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 5,
				max: 1800,
				min: 0,
				labels: {
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'left',
				fontFamily: "Inter",
				fontWeight: 400,
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			},
		};
		var chart = new ApexCharts(document.querySelector("#answered_calls"), options);
		chart.render();
	}

	/* Missed Calls JS*/
	const getMissedCallsId = document.getElementById('missed_calls');
	if (getMissedCallsId) {
		var options = {
			series: [
				{
					name: "Total Calls",
					data: [200, 150, 500, 250, 500, 400, 700, 600, 900, 800, 600, 1300]
				},
			],
			chart: {
				type: "area",
				height: 325,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			colors: [
				"#EE3E08", "#EE3E08"
			],
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: "smooth",
				width: [2, 2, 0],
				dashArray: [0, 6, 0],
			},
			grid: {
				borderColor: "#ffffff"
			},
			fill: {
				type: 'gradient',
				gradient: {
					stops: [0, 90, 100],
					shadeIntensity: 1,
					opacityFrom: 0,
					opacityTo: 0.5
				}
			},
			xaxis: {
				categories: [
					"Jan",
					"Feb",
					"Mar",
					"Apr",
					"May",
					"Jun",
					"Jul",
					"Aug",
					"Sep",
					"Oct",
					"Nov",
					"Dec"
				],
				axisTicks: {
					show: false,
					color: '#B1BBC8'
				},
				axisBorder: {
					show: false,
					color: '#B1BBC8'
				},
				labels: {
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 5,
				max: 1800,
				min: 0,
				labels: {
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'left',
				fontFamily: "Inter",
				fontWeight: 400,
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			},
		};
		var chart = new ApexCharts(document.querySelector("#missed_calls"), options);
		chart.render();
	}

	/* Inbound Calls JS*/
	const getInboundCallsId = document.getElementById('inbound_calls');
	if (getInboundCallsId) {
		var options = {
			series: [
				{
					name: "Inbound Calls",
					data: [100, 130, 115, 170, 110, 120, 85, 140, 150, 100, 110, 90, 160, 125, 105, 130, 145, 120, 150, 155, 220, 165]
				}
			],
			chart: {
				type: "area",
				height: 164,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: "straight",
				width: 1
			},
			colors: [
				"#605DFF"
			],
			fill: {
				type: 'gradient',
				gradient: {
					stops: [0, 90, 100],
					shadeIntensity: 1,
					opacityFrom: 0.5,
					opacityTo: 0.6
				}
			},
			xaxis: {
				categories: [
					"01 Jan",
					"02 Jan",
					"03 Jan",
					"04 Jan",
					"05 Jan",
					"06 Jan",
					"07 Jan",
					"08 Jan",
					"09 Jan",
					"10 Jan",
					"11 Jan",
					"12 Jan",
					"13 Jan",
					"14 Jan",
					"15 Jan",
					"16 Jan",
					"17 Jan",
					"18 Jan",
					"19 Jan",
					"20 Jan",
					"21 Jan",
					"22 Jan",
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 5,
				show: false,
				max: 250,
				min: 0,
				labels: {
					show: true,
					style: {
						colors: "#64748B",
						fontSize: "12px"
					},
				},
				axisBorder: {
					show: true,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: true,
					color: '#ECEEF2'
				}
			},
			grid: {
				show: false,
				borderColor: "#ECEEF2"
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				fontFamily: "Inter",
				fontWeight: 400,
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#inbound_calls"), options);
		chart.render();
	}

	/* Outbound Calls JS*/
	const getOutboundCallsId = document.getElementById('outbound_calls');
	if (getOutboundCallsId) {
		var options = {
			series: [
				{
					name: "Outbound Calls",
					data: [100, 130, 115, 170, 110, 120, 85, 140, 150, 100, 110, 90, 160, 125, 105, 130, 145, 120, 150, 155, 220, 165]
				}
			],
			chart: {
				type: "area",
				height: 164,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: "smooth", // ['straight', 'smooth', 'monotoneCubic', 'stepline']
				width: 1
			},
			colors: [
				"#9135E8"
			],
			fill: {
				type: 'gradient',
				gradient: {
					stops: [0, 90, 100],
					shadeIntensity: 1,
					opacityFrom: 0.5,
					opacityTo: 0.6
				}
			},
			xaxis: {
				categories: [
					"01 Jan",
					"02 Jan",
					"03 Jan",
					"04 Jan",
					"05 Jan",
					"06 Jan",
					"07 Jan",
					"08 Jan",
					"09 Jan",
					"10 Jan",
					"11 Jan",
					"12 Jan",
					"13 Jan",
					"14 Jan",
					"15 Jan",
					"16 Jan",
					"17 Jan",
					"18 Jan",
					"19 Jan",
					"20 Jan",
					"21 Jan",
					"22 Jan",
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 5,
				show: false,
				max: 250,
				min: 0,
				labels: {
					show: true,
					style: {
						colors: "#64748B",
						fontSize: "12px"
					},
				},
				axisBorder: {
					show: true,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: true,
					color: '#ECEEF2'
				}
			},
			grid: {
				show: false,
				borderColor: "#ECEEF2"
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				fontFamily: "Inter",
				fontWeight: 400,
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#outbound_calls"), options);
		chart.render();
	}

	/* Agent Avg Earnings JS */ 
	const getAgentAvgEarningsId = document.getElementById('agent_avg_earnings');
	if (getAgentAvgEarningsId) {
		var options = {
			series: [
				{
					name: "Agent Avg Earnings",
					data: [20, 40, 60, 60, 50, 30, 20, 20, 40, 40, 60, 60]
				}
			],
			chart: {
				type: "area",
				height: 260,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			colors: [
				"#9135E8"
			],
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: "stepline", //curve: ['straight', 'smooth', 'monotoneCubic', 'stepline']
				width: 3,
				lineCap: "10",
			},
			grid: {
				borderColor: '#ECF0FF', 
				strokeDashArray: 10,
				xaxis: {
					lines: {
						show: false,
					}
				},
			},
			fill: {
				type: 'gradient',
				gradient: {
					stops: [0, 90, 100],
					shadeIntensity: 1,
					opacityFrom: 0,
					opacityTo: 0.8
				}
			},
			xaxis: {
				categories: [
					"NOV 16",
					"17",
					"18",
					"19",
					"20",
					"21",
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 4,
				max: 80,
				min: 0,
				labels: {
					show: false,
					formatter: (val) => {
						return '$' + val + 'K'
					},
					style: {
						colors: "#9C9AB6",
						fontSize: "12px",
						fontWeight: 500,
					}
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				}
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				fontFamily: "Inter",
				fontWeight: 400,
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#agent_avg_earnings"), options);
		chart.render();
	}
	
	/* Agent Avg Earnings 2 JS */ 
	const getAgentAvgEarnings2Id = document.getElementById('agent_avg_earnings2');
	if (getAgentAvgEarnings2Id) {
		var options = {
			series: [
				{
					name: "Agent Avg Earnings",
					data: [60, 60, 40, 40, 20, 20, 30, 50, 60, 60, 40, 20],
				}
			],
			chart: {
				type: "area",
				height: 260,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			colors: [
				"#9135E8"
			],
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: "stepline", //curve: ['straight', 'smooth', 'monotoneCubic', 'stepline']
				width: 3,
				lineCap: "10",
			},
			grid: {
				borderColor: '#ECF0FF', 
				strokeDashArray: 10,
				xaxis: {
					lines: {
						show: false,
					}
				},
			},
			fill: {
				type: 'gradient',
				gradient: {
					stops: [0, 90, 100],
					shadeIntensity: 1,
					opacityFrom: 0,
					opacityTo: 0.8
				}
			},
			xaxis: {
				categories: [
					"NOV 16",
					"17",
					"18",
					"19",
					"20",
					"21",
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 4,
				max: 80,
				min: 0,
				labels: {
					show: false,
					formatter: (val) => {
						return '$' + val + 'K'
					},
					style: {
						colors: "#9C9AB6",
						fontSize: "12px",
						fontWeight: 500,
					}
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				}
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				fontFamily: "Inter",
				fontWeight: 400,
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#agent_avg_earnings2"), options);
		chart.render();
	} 

	/* Agent Avg Earnings 3 JS */ 
	const getAgentAvgEarnings3Id = document.getElementById('agent_avg_earnings3');
	if (getAgentAvgEarnings3Id) {
		var options = {
			series: [
				{
					name: "Agent Avg Earnings",
					data: [20, 40, 60, 60, 50, 30, 20, 20, 40, 40, 60, 60]
				}
			],
			chart: {
				type: "area",
				height: 260,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			colors: [
				"#9135E8"
			],
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: "stepline", //curve: ['straight', 'smooth', 'monotoneCubic', 'stepline']
				width: 3,
				lineCap: "10",
			},
			grid: {
				borderColor: '#ECF0FF', 
				strokeDashArray: 10,
				xaxis: {
					lines: {
						show: false,
					}
				},
			},
			fill: {
				type: 'gradient',
				gradient: {
					stops: [0, 90, 100],
					shadeIntensity: 1,
					opacityFrom: 0,
					opacityTo: 0.8
				}
			},
			xaxis: {
				categories: [
					"NOV 16",
					"17",
					"18",
					"19",
					"20",
					"21",
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 4,
				max: 80,
				min: 0,
				labels: {
					show: false,
					formatter: (val) => {
						return '$' + val + 'K'
					},
					style: {
						colors: "#9C9AB6",
						fontSize: "12px",
						fontWeight: 500,
					}
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				}
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				fontFamily: "Inter",
				fontWeight: 400,
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#agent_avg_earnings3"), options);
		chart.render();
	}

	/* Agent Avg Earnings 4 JS */ 
	const getAgentAvgEarnings4Id = document.getElementById('agent_avg_earnings4');
	if (getAgentAvgEarnings4Id) {
		var options = {
			series: [
				{
					name: "Agent Avg Earnings",
					data: [60, 60, 40, 40, 20, 20, 30, 50, 60, 60, 40, 20],
				}
			],
			chart: {
				type: "area",
				height: 260,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			colors: [
				"#9135E8"
			],
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: "stepline", //curve: ['straight', 'smooth', 'monotoneCubic', 'stepline']
				width: 3,
				lineCap: "10",
			},
			grid: {
				borderColor: '#ECF0FF', 
				strokeDashArray: 10,
				xaxis: {
					lines: {
						show: false,
					}
				},
			},
			fill: {
				type: 'gradient',
				gradient: {
					stops: [0, 90, 100],
					shadeIntensity: 1,
					opacityFrom: 0,
					opacityTo: 0.8
				}
			},
			xaxis: {
				categories: [
					"NOV 16",
					"17",
					"18",
					"19",
					"20",
					"21",
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 4,
				max: 80,
				min: 0,
				labels: {
					show: false,
					formatter: (val) => {
						return '$' + val + 'K'
					},
					style: {
						colors: "#9C9AB6",
						fontSize: "12px",
						fontWeight: 500,
					}
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				}
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				fontFamily: "Inter",
				fontWeight: 400,
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#agent_avg_earnings4"), options);
		chart.render();
	} 

	/* Agent Avg Earnings 5 JS */ 
	const getAgentAvgEarnings5Id = document.getElementById('agent_avg_earnings5');
	if (getAgentAvgEarnings5Id) {
		var options = {
			series: [
				{
					name: "Agent Avg Earnings",
					data: [20, 40, 60, 60, 50, 30, 20, 20, 40, 40, 60, 60]
				}
			],
			chart: {
				type: "area",
				height: 260,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			colors: [
				"#9135E8"
			],
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: "stepline", //curve: ['straight', 'smooth', 'monotoneCubic', 'stepline']
				width: 3,
				lineCap: "10",
			},
			grid: {
				borderColor: '#ECF0FF', 
				strokeDashArray: 10,
				xaxis: {
					lines: {
						show: false,
					}
				},
			},
			fill: {
				type: 'gradient',
				gradient: {
					stops: [0, 90, 100],
					shadeIntensity: 1,
					opacityFrom: 0,
					opacityTo: 0.8
				}
			},
			xaxis: {
				categories: [
					"NOV 16",
					"17",
					"18",
					"19",
					"20",
					"21",
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 4,
				max: 80,
				min: 0,
				labels: {
					show: false,
					formatter: (val) => {
						return '$' + val + 'K'
					},
					style: {
						colors: "#9C9AB6",
						fontSize: "12px",
						fontWeight: 500,
					}
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				}
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				fontFamily: "Inter",
				fontWeight: 400,
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#agent_avg_earnings5"), options);
		chart.render();
	}


	//**<---- Dashboard Doctor ---->**//


	/* Patient Retention JS*/
	const getPatientRetentionId = document.getElementById('patient_retention');
	if (getPatientRetentionId) {
		var options = {
			series: [
				{
					name: "Old Patient",
					data: [35, 50, 55, 60, 50, 60, 55, 60, 78, 40, 30]
				},
				{
					name: "New Patient",
					data: [70, 50, 40, 40, 62, 52, 80, 40, 60, 53, 40]
				},
			],
			chart: {
				type: "area",
				height: 335,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false,
				}
			},
			colors: [
				"#00E396", "#26A0FC" 
			],
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: "straight",
				width: [4, 4, 0],
				dashArray: [6, 0, 0],
			},
			grid: {
				borderColor: "#ECF0FF"
			},
			fill: {
				type: 'gradient',
				gradient: {
					stops: [0, 90, 100],
					shadeIntensity: 1,
					opacityFrom: 0,
					opacityTo: 0
				}
			},
			xaxis: {
				categories: [
					"Oct",
					"02",
					"03",
					"04",
					"05",
					"06",
					"07",
					"08",
					"09",
					"10",
					"11",
				],
				axisTicks: {
					show: true,
					color: '#E0E0E0'
				},
				axisBorder: {
					show: true,
					color: '#E0E0E0'
				},
				labels: {
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 5,
				max: 150,
				min: 0,
				axisBorder: {
					show: true,
					color: '#E0E0E0'
				},
				labels: {
					formatter: (val) => {
						return '' + val / 1 + 'K'
					},
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			legend: {
				show: true,
				position: 'bottom',
				fontSize: '12px',
				horizontalAlign: 'center',
				fontFamily: "Inter",
				fontWeight: 400,
				offsetY: 10,
				itemMargin: {
					horizontal: 8,
					vertical: 10
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			},
			tooltip: {
				y: {
					formatter: function(val) {
					return "" + val + "K";
					}
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#patient_retention"), options);
		chart.render();
	}

	/* Patient Distribution JS*/
	const getPatientDistributionId = document.getElementById('patient_distribution');
	if (getPatientDistributionId) {
		var options = {
			series: [24.5, 23.5, 24.5],
			chart: {
				height: 314,
				type: "donut"
			},
			labels: [
				"Male", "Female", "Children"
			],
			colors: [
				"#605DFF", "#58F229", "#5DA8FF"
			],
			stroke: {
				width: 5,
				show: true,
				colors: ["#ffffff"],
				lineCap: 'round'
			},
			legend: {
				show: true,
				position: 'bottom',
				fontSize: '12px',
				horizontalAlign: 'center',
				fontFamily: "Inter",
				fontWeight: 400,
				offsetY: 10,
				itemMargin: {
					horizontal: 8,
					vertical: 10
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			},
			plotOptions: {
				pie: {
					expandOnClick: false,
					donut: {
						size: '62%',
						labels: {
							show: true,
							name: {
								color: '#64748B'
							},
							value: {
								show: true,
								color: '#000',
								fontSize: '36px',
								fontWeight: '700',
								offsetY: 10,
								formatter: (val) => {
									return '' + val / 1 + 'K'
								},
							},
							total: {
								show: true,
								color: '#64748B',
								fontSize: '14px',
								fontWeight: '400',
								
							}
						}
					}
				}
			},
			dataLabels: {
				enabled: false
			},
			tooltip: {
				enabled: true,
				y: {
					formatter: (val) => {
						return '' + val / 1 + 'K'
					},
				}
			}
		}
		var chart = new ApexCharts(document.querySelector("#patient_distribution"), options);
		chart.render();
	}

	/* Income Vs Expense JS*/
	const getIncomeVsExpenseId = document.getElementById('income_vs_expense');
	if (getIncomeVsExpenseId) {
		var options = {
			series: [
				{
					name: "Income",
					data: [70, 42, 70, 120, 40, 70, 90, 20, 60],
				},
				{
					name: "Expense",
					data: [-70, -44, -70, -120, -40, -70, -90, -20, -40],
				},
			],
			colors: ['#605DFF', '#C2CDFF'],
			chart: {
				type: 'bar',
				height: 386,
				stacked: true,
				toolbar: {
					show: false,
				}
			},
			plotOptions: {
				bar: {
					columnWidth: '30%',
					borderRadius: 2,
					borderRadiusApplication: "end",
					borderRadiusWhenStacked: "all",
				},
			},
			  dataLabels: {
				enabled: false,
			},
			grid: {
				borderColor: '#ECF0FF', 
				xaxis: {
					lines: {
						show: false
					}
				},
				yaxis: {
					lines: {
						show: true
					}
				},
			},
			legend: {
				show: true,
				position: 'bottom',
				fontSize: '12px',
				horizontalAlign: 'center',
				fontFamily: "Inter",
				fontWeight: 400,
				offsetY: 10,
				itemMargin: {
					horizontal: 8,
					vertical: 10
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			},
			xaxis: {
				categories: [
					"Oct 01",
					"Oct 02",
					"Oct 03",
					"Oct 04",
					"Oct 05",
					"Oct 06",
					"Oct 07",
					"Oct 08",
					"Oct 09",
				],
				axisTicks: {
					show: false,
					color: '#8695AA'
				},
				axisBorder: {
					show: false,
					color: '#8695AA'
				},
				labels: {
					show: true,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				show: true,
				labels: {
					formatter: function (val) {
						return " " + val + " K"
					},
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
				
			},
			fill: {
				opacity: 1
			},
			tooltip: {
				y: {
					formatter: function (val) {
						return "" + val + "K"
					}
				}
			},
		};
		var chart = new ApexCharts(document.querySelector("#income_vs_expense"), options);
		chart.render();
	}


	//**<---- Dashboard Beauty Salon ---->**//


	/* Customer Satisfaction Rate JS */ 
	const getCustomerSatisfactionRateId = document.getElementById('customer_satisfaction_rate');
	if (getCustomerSatisfactionRateId) {
		var options = {

			series: [
				85, 15,
			],
			chart: {
				height: 202,
				type: "pie",
			},
			labels: [
				"Positive", "Negative"
			],
			colors: [
				"#9CAAFF", "#FFAA72"
			],
			dataLabels: {
				enabled: false,
				style: {
					fontSize: '10px',
					fontFamily: 'Inter',
					colors: ['#fff']
				},
				dropShadow: {
					enabled: true,
					top: 0,
					left: 0,
					blur: 0,
					color: '#000',
					opacity: 0
				}
			},
			plotOptions: {
				pie: {
					expandOnClick: false,
				},
				
			},
			tooltip: {
				y: {
					formatter: function(val) {
						return val + "%";
					}
				}
			},
			stroke: {
				width: 1,
				show: true,
				colors: ["#ffffff"]
			},
			legend: {
				position: 'bottom',
				horizontalAlign: 'center', 
				fontWeight: 400,
				fontFamily: 'Inter',
				fontSize: '12',
				offsetY: 0,
				labels: {
					colors: ['#64748B', '#64748B'],
				},
				itemMargin: {
					horizontal: 10,
					vertical: 5,
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			},
		};
		var chart = new ApexCharts(document.querySelector("#customer_satisfaction_rate"), options);
		chart.render();
	}

	/* Revenue By Services JS */ 
	const getRevenueByServicesId = document.getElementById('revenue_by_services');
	if (getRevenueByServicesId) {

		var options = {
			series: [{
				name: 'Facial',
				data: [44, 22, 7, 57, 44, 57, 18, 43, 24]
		  	}, {
				name: 'Manicure',
				data: [13, 23, 10, 13, 13, 13, 13, 13, 13]
		  	}, {
				name: 'Pedicure',
				data: [11, 17, 6, 15, 11, 15, 11, 11, 11]
		  	}, {
				name: 'Hair Cut',
				data: [21, 7, 25, 21, 21, 21, 21, 21, 21]
		  	}],
			chart: {
				type: 'bar',
				height: 321,
				stacked: true,
				toolbar: {
					show: false
				},
				zoom: {
					enabled: true
				}
		  	},
			colors: ['#82FC5A', '#D7B5FD', '#90C7FF', '#9CAAFF'],
			dataLabels: {
				style: {
					fontSize: '10px',
					fontFamily: 'Inter',
					fontWeight: '700',
					colors: ['#343A46']
				},
			},
			plotOptions: {
				bar: {
				horizontal: false,
				columnWidth: '55%',
				borderRadius: 6,
				borderRadiusApplication: 'end', // 'around', 'end'
				borderRadiusWhenStacked: 'last', // 'all', 'last'
				dataLabels: {
						total: {
							enabled: false,
						}
					}
				},
			},
			xaxis: {
				show: true,
				categories: [
					"Oct 01",
					"Oct 02",
					"Oct 03",
					"Oct 04",
					"Oct 05",
					"Oct 06",
					"Oct 07",
					"Oct 08",
					"Oct 09",
				],
				axisTicks: {
					show: false,
					color: '#B1BBC8'
				},
				axisBorder: {
					show: false,
					color: '#B1BBC8'
				},
				labels: {
					show: true,
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				show: true,
				tickAmount: 6,
				//max: 100,
				min: 0,
				labels: {
					style: {
						colors: "#8695AA", 
						fontSize: "12px"
					}
				}
			},
		  	grid: {
				borderColor: "#ECF0FF"
			},
			legend: {
				show: true,
				position: 'bottom',
				fontSize: '12px',
				horizontalAlign: 'center',
				fontFamily: "Inter",
				fontWeight: 400,
				offsetY: 10,
				itemMargin: {
					horizontal: 8,
					vertical: 10
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			},
			fill: {
				opacity: 1
			}
		};
		var chart = new ApexCharts(document.querySelector("#revenue_by_services"), options);
		chart.render();
	}


	//**<---- Dashboard Store Analytics ---->**//

	/* Gross Revenue JS*/
	const getGrossRevenue2Id = document.getElementById('gross_revenue2');
	if (getGrossRevenue2Id) {
		var options = {
			series: [
				{
					name: "Sales Revenue",
					data: [35, 50, 55, 60, 50, 60, 55, 60, 78, 40]
				},
				
				{
					name: "Sales Volume",
					data: [80, 60, 50, 50, 72, 65, 90, 50, 70, 63]
				},
				{
					name: "Order Value",
					data: [70, 50, 40, 40, 95, 52, 80, 40, 60, 53]
				},
			],
			chart: {
				type: "area",
				height: 339,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false,
				}
			},
			colors: [
				"#757DFF", "#E9D5FF", "#37D80A"
			],
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: "straight",
				width: [3, 3, 3],
				dashArray: [0, 6, 6],
			},
			grid: {
				borderColor: "#ECF0FF"
			},
			fill: {
				type: 'gradient',
				gradient: {
					stops: [0, 90, 100],
					shadeIntensity: 1,
					opacityFrom: 0,
					opacityTo: 0
				}
			},
			xaxis: {
				categories: [
					"Oct 01",
					"Oct 04",
					"Oct 08",
					"Oct 12",
					"Oct 16",
					"Oct 20",
					"Oct 24",
					"Oct 28",
					"Nov 02",
					"Nov 06",
				],
				axisTicks: {
					show: false,
					color: '#B1BBC8'
				},
				axisBorder: {
					show: false,
					color: '#B1BBC8'
				},
				labels: {
					style: {
						colors: "#8695AA",
						fontSize: "12px",
						fontFamily: 'Inter',
					}
				}
			},
			yaxis: {
				tickAmount: 5,
				max: 100,
				min: 0,
				labels: {
					formatter: (val) => {
						return '$' + val / 1 + 'K'
					},
					style: {
						colors: "#8695AA",
						fontSize: "12px",
						fontFamily: 'Inter',
					}
				}
			},
			legend: {
				show: true,
				position: 'bottom',
				fontSize: '12px',
				horizontalAlign: 'center',
				fontFamily: "Inter",
				fontWeight: 400,
				offsetY: 10,
				itemMargin: {
					horizontal: 8,
					vertical: 10
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			},
			tooltip: {
				y: {
					formatter: function(val) {
					return "$" + val + "k";
					}
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#gross_revenue2"), options);
		chart.render();
	}

	/* Sales By Gender JS*/
	const getSalesByGenderId = document.getElementById('sales_by_gender');
	if (getSalesByGenderId) {
		var options = {
			series: [70, 30],
			chart: {
				height: 110,
				type: "donut"
			},
			labels: [
				"Male", "Female",
			],
			colors: [
				"#605DFF", "#AD63F6"
			],
			stroke: {
				width: 5,
				show: true,
				colors: ["#ffffff"],
				lineCap: 'round'
			},
			legend: {
				show: true,
				position: 'right',
				fontSize: '12px',
				horizontalAlign: 'center',
				fontFamily: "Inter",
				fontWeight: 400,
				offsetY: 10,
				itemMargin: {
					horizontal: 0,
					vertical: 5
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			},
			plotOptions: {
				pie: {
					expandOnClick: false,
					donut: {
						size: '60%',
						labels: {
							show: false,
							name: {
								color: '#64748B'
							},
							value: {
								show: false,
								color: '#000',
								fontSize: '36px',
								fontWeight: '700',
								offsetY: 10,
								formatter: (val) => {
									return '' + val / 1 + '%'
								},
							},
							total: {
								show: false,
								color: '#64748B',
								fontSize: '14px',
								fontWeight: '400',
								
							}
						}
					}
				}
			},
			dataLabels: {
				enabled: false
			},
			tooltip: {
				enabled: true,
				y: {
					formatter: (val) => {
						return '' + val / 1 + '%'
					},
				}
			}
		}
		var chart = new ApexCharts(document.querySelector("#sales_by_gender"), options);
		chart.render();
	}

	/* Sales This Month JS */ 
	const getSalesThisMonthId = document.getElementById('sales_this_month');
	if (getSalesThisMonthId) {
		var options = {
			series: [{
				name: "Sales This Month",
				data: [10, 31, 25, 40, 50, 50, 100, 90, 90]
			}],
			chart: {
				height: 170,
				type: 'line',
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				},
			},
			markers: {
				size: 0,
				strokeWidth: 0,
				hover: {
				  size: 0
				}
			},
			colors: ['#37D80A'],
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: 'straight',
				width: 6,
			},
			grid: {
				borderColor: "#ECF0FF"
			},
			xaxis: {
				show: false,
				categories: [
					"3am",
					"6am",
					"9am",
					"12pm",
					"3pm",
					"6pm",
					"9pm",
					"12am",
				],
				axisTicks: {
					show: false,
					color: '#B1BBC8'
				},
				axisBorder: {
					show: false,
					color: '#B1BBC8'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px",
						fontFamily: 'Inter',
					}
				}
			},
			yaxis: {
				show: false,
				tickAmount: 3,
				labels: {
					style: {
						colors: "#8695AA", 
						fontSize: "12px"
					}
				}
			},
			tooltip: {
				y: {
					formatter: (val) => {
						return '$' + val + 'K'
					},
				}
			},
		};
		var chart = new ApexCharts(document.querySelector("#sales_this_month"), options);
		chart.render();
	}

	/* Sales By Category JS */
	const getSalesByCategoryId = document.getElementById('sales_by_category');
	if (getSalesByCategoryId) {
		var options = {
			series: [
				{
					name: 'Electronics',
					data: [50, 60, 80, 25, 10, 80],
				}, 
				{
					name: 'Non-electronics',
					data: [20, 100, 40, 30, 80, 33],
				},
			],
			chart: {
				height: 399,
				type: 'radar',
				toolbar: {
					show: false,
				}
			},
			dataLabels: {
				enabled: true
			},
			legend: {
				position: 'bottom',
				horizontalAlign: 'center', 
				fontWeight: 400,
				fontFamily: 'Inter',
				fontSize: '12',
				offsetY: 0,
				labels: {
					colors: ['#64748B', '#64748B'],
				},
				itemMargin: {
					horizontal: 10,
					vertical: 5,
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			},
			plotOptions: {
				radar: {
				size: 115,
				polygons: {
					strokeColors: '#E9E9E9',
					fill: {
						colors: ['#F8F8F8', '#ffffff']
					}
				}
				}
			},
			colors: ['#757DFF', '#FC6829'],
			tooltip: {
				y: {
					formatter: function(val) {
						return val
					}
				}
			},
			xaxis: {
				categories: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
				labels: {
					style: {
						colors: "#A8A8A8",
						fontSize: "11px",
						fontFamily: 'Inter',
						fontWeight: 400,
					}
				}
			},
			yaxis: {
				tickAmount: 5,
				labels: {
				formatter: function(val, i) 
					{
						if (i % 2 === 0) {
							return val
						} else {
							return ''
						}
					}
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#sales_by_category"), options);
		chart.render();
	}


	//**<---- Dashboard Social ---->**//
	

	/* Linkedin Net Followers JS*/
	const getLinkedinNetFollowersId = document.getElementById('linkedin_net_followers');
	if (getLinkedinNetFollowersId) {
		var options = {
			series: [
				{
					name: "Followers",
					data: [250, 150, 250, 120, 350, 150, 250]
				},
			],
			chart: {
				type: "area",
				height: 293,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				},
				dropShadow: {
					enabled: true,
					enabledOnSeries: [0], // Apply shadow to the correct series index
					top: 5,
					left: 5,
					blur: 3,
					opacity: 0.6,
					color: "#605DFF" // Custom color for the shadow
				}
			},
			colors: [
				"#605DFF", "#DDE4FF"
			],
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: "smooth",
				width: [1],
			},
			grid: {
				borderColor: '#ECEEF2',
				strokeDashArray: 8,
				xaxis: {
					lines: {
						show: false,
					}
				},
			},
			fill: {
				type: 'gradient',
				gradient: {
					stops: [0, 90, 100],
					shadeIntensity: 1,
					opacityFrom: 0.4,
					opacityTo: 0.0
				}
			},
			xaxis: {
				categories: [
					"Mon",
					"Tue",
					"Wed",
					"Thu",
					"Fri",
					"Sat",
					"Sun",
				],
				axisTicks: {
					show: false,
					color: '#B1BBC8'
				},
				axisBorder: {
					show: true,
					color: '#D5D9E2'
				},
				labels: {
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			yaxis: {
				tickAmount: 5,
				max: 450,
				min: 0,
				axisBorder: {
					show: true,
					color: '#D5D9E2'
				},
				labels: {
					style: {
						colors: "#8695AA",
						fontSize: "12px"
					}
				}
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'left',
				fontFamily: "Inter",
				fontWeight: 400,
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			},
		};
		var chart = new ApexCharts(document.querySelector("#linkedin_net_followers"), options);
		chart.render();
		
	}

	/* Followers by Gender JS */ 
	const getFollowersByGenderId = document.getElementById('followers_by_gender');
	if (getFollowersByGenderId) {
		var options = {

			series: [
				55, 45,
			],
			chart: {
				height: 192,
				type: "pie",
			},
			labels: [
				"Female Followers", "Male Followers"
			],
			colors: [
				"#605DFF", "#AD63F6"
			],
			dataLabels: {
				enabled: false,
				style: {
					fontSize: '10px',
					fontFamily: 'Inter',
					colors: ['#fff']
				},
				dropShadow: {
					enabled: true,
					top: 0,
					left: 0,
					blur: 0,
					color: '#000',
					opacity: 0
				}
			},
			plotOptions: {
				pie: {
					expandOnClick: false,
				},
				
			},
			tooltip: {
				y: {
					formatter: function(val) {
						return val + "%";
					}
				}
			},
			stroke: {
				width: 1,
				show: true,
				colors: ["#ffffff"]
			},
			legend: {
				show: false,
				position: 'bottom',
				horizontalAlign: 'center', 
				fontWeight: 400,
				fontFamily: 'Inter',
				fontSize: '12',
				offsetY: 0,
				labels: {
					colors: ['#64748B', '#64748B'],
				},
				itemMargin: {
					horizontal: 10,
					vertical: 5,
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			},
		};
		var chart = new ApexCharts(document.querySelector("#followers_by_gender"), options);
		chart.render();
	}

	// Facebook Campaign Overview JS
	const getFacebookCampaignOverviewId = document.getElementById('facebook_campaign_overview');
	if (getFacebookCampaignOverviewId) {
		var options = {
			series: [
				{
					name: 'Impressions',
					type: 'column',
					data: [400, 600, 300, 700, 450, 400, 600]
				},
				{
					name: 'Cost per Conversion',
					type: 'line',
					data: [500, 700, 600, 900, 700, 800, 950]
				},
				{
					name: 'Clicks',
					type: 'column',
					data: [500, 380, 500, 600, 340, 400, 250]
				},
				{
					name: 'CTR',
					type: 'column',
					data: [400, 350, 300, 250, 500, 600, 400]
				},
			],
			chart: {
				type: 'line',
				height: 292,
				toolbar: {
					show: false,
				},
			},
			colors: ["#605DFF", '#BF85FB', '#37D80A', '#FD5812'],
			grid: {
				borderColor: '#ECEEF2',
				strokeDashArray: 8,
				xaxis: {
					lines: {
						show: false,
					}
				},
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				width: [2, 2.1],
				curve: ['straight', 'smooth'],
			},
			xaxis: {
				categories: [
					"Jan",
					"Feb",
					"Mar",
					"Apr",
					"May",
					"Jun",
					"July"
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: true,
					color: '#D5D9E2'
				},
				labels: {
					show: true,
					style: {
						colors: "#64748B",
						fontSize: "12px",
						fontWeight: 400,
						fontFamily: 'Inter',
					}
				}
			},
			yaxis: {
				tickAmount: 5,
				max: 1000,
				min: 0,
				labels: {
					style: {
						colors: "#64748B",
						fontSize: "12px",
						fontWeight: 400,
						fontFamily: 'Inter',
					},
					formatter: (val) => {
						return '$' + val + ''
					},
				},
				axisBorder: {
					show: true,
					color: '#D5D9E2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				}
			},
			legend: {
				show: false,
			},
			plotOptions: {
				bar: {
					columnWidth: "25%",
					borderRadius: 2
				},
			},
			tooltip: {
				y: {
					formatter: (val) => {
						return '$' + val + ''
					},
				}
			},
		};
		var chart = new ApexCharts(document.querySelector("#facebook_campaign_overview"), options);
		chart.render();
	}


	//**<---- Dashboard POS System ---->**//


	/* Sales Over Time JS */
	const getSalesOverTimeId = document.getElementById('sales_over_time');
	if (getSalesOverTimeId) {
		var options = {
			series: [
				{
					name: "Sales Over Time",
					data: [60, 80, 50, 60, 70, 40, 80]
				}
			],
			chart: {
				type: "bar",
				height: 197,
				toolbar: {
					show: false
				}
			},
			colors: [
				"#C2CDFF"
			],
			plotOptions: {
				bar: {
					columnWidth: "30%",
					borderRadius: 4,
				}
			},
			fill: {
				opacity: [0.85, 0.25, 1],
				gradient: {
					inverseColors: false,
					shade: 'light',
					type: "vertical",
					opacityFrom: 0.85,
					opacityTo: 0.55,
					stops: [0, 100, 100, 100]
				}
			},
			grid: {
				show: false,
				borderColor: "#ECEEF2"
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				width: 0,
				show: true,
				colors: ["transparent"]
			},
			xaxis: {
				categories: [
					"Sun",
					"Mon",
					"Tue",
					"Wed",
					"Thu",
					"Fri",
					"Sat",
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: true,
					style: {
						colors: "#8695AA",
						fontSize: "12px",
						fontFamily: 'Inter',
					}
				}
			},
			yaxis: {
				show: false,
				tickAmount: 4,
				labels: {
					style: {
						colors: "#8695AA",
						fontSize: "12px",
						fontFamily: 'Inter',
					},
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
			},
			tooltip: {
				y: {
					formatter: (val) => {
						return '$' + val + 'K'
					},
				}
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'left',
				fontFamily: "Inter",
				fontWeight: 400,
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			},
			states: {
				hover: {
					filter: {
						type: 'darken',
						value: 0.1,
					}
				},
			}
		};
		var chart = new ApexCharts(document.querySelector("#sales_over_time"), options);
		chart.render();
	}

	/* Sales By Category/Products JS */
	const getSalesByCategoryProductsId = document.getElementById('sales_by_category_products');
	if (getSalesByCategoryProductsId) {

		var options = {
			series: [35000, 25000, 18000],
			chart: {
				type: 'donut',
				height: 178,
		  	},
			  labels: [
				"Electronics", "Clothing", "Home Goods"
			],
			colors: [
				"#AD63F6", "#37D80A", "#3584FC"
			],
			plotOptions: {
				pie: {
					startAngle: -90,
					endAngle: 90,
					offsetY: 10
				}
			},
			grid: {
				padding: {
					bottom: -80
				}
			},
			responsive: [{
				breakpoint: 480,
				options: {
					chart: {
						width: 200
					},
					legend: {
						position: 'bottom'
					}
				}
			}],
			legend: {
				show: false,
			},
			dataLabels: {
				enabled: false
			},
			tooltip: {
				enabled: true,
				y: {
					formatter: (val) => {
						return '$' + val / 1 + ''
					},
				}
			},
			stroke: {
				width: 2,
				show: true,
				colors: ["#ffffff"],
				lineCap: 'round'
			},
		};
  
		var chart = new ApexCharts(document.querySelector("#sales_by_category_products"), options);
		chart.render();

	}

	/* Customer Segmentation JS*/
	const getCustomerSegmentationId = document.getElementById('customer_segmentation');
	if (getCustomerSegmentationId) {
		var options = {
			series: [1200, 1800],
			chart: {
				height: 213,
				type: "donut"
			},
			labels: [
				"New Customers", "Returning Customers",
			],
			colors: [
				"#3584FC", "#AD63F6"
			],
			stroke: {
				width: 1,
				show: true,
				colors: ["#ffffff"]
			},
			legend: {
				show: false,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				fontFamily: "Inter",
				fontWeight: 400,
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					shape: 'diamond',
				}
			},
			plotOptions: {
				pie: {
					expandOnClick: false,
					donut: {
						size: '80%',
						labels: {
							show: true,
							name: {
								color: '#64748B'
							},
							value: {
								show: true,
								color: '#64748B',
								fontSize: '24px',
								fontWeight: '600',
								offsetY: -0,
							},
							total: {
								show: true,
								color: '#64748B',
								fontSize: '14px',
								fontWeight: '400',
								
							}
						}
					}
				}
			},
			dataLabels: {
				enabled: false
			},
			tooltip: {
				enabled: false
			}
		}
		var chart = new ApexCharts(document.querySelector("#customer_segmentation"), options);
		chart.render();
	}


	//**<---- Dashboard Podcast ---->**//


	/* Outbound Calls JS*/
	const getTodaysEarningsId = document.getElementById('todays_earnings');
	if (getTodaysEarningsId) {
		var options = {
			series: [
				{
					name: "Today’s Earnings",
					data: [100, 130, 115, 170, 110, 120, 85, 140, 150, 100, 110, 90, 160, 125, 105, 130, 145, 120, 150, 155, 220, 165]
				}
			],
			chart: {
				type: "area",
				height: 164,
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: "smooth", // ['straight', 'smooth', 'monotoneCubic', 'stepline']
				width: 1
			},
			colors: [
				"#9135E8"
			],
			fill: {
				type: 'gradient',
				gradient: {
					stops: [0, 100, 100],
					shadeIntensity: 1,
					opacityFrom: 0.5,
					opacityTo: 0.6
				}
			},
			xaxis: {
				categories: [
					"01 Jan",
					"02 Jan",
					"03 Jan",
					"04 Jan",
					"05 Jan",
					"06 Jan",
					"07 Jan",
					"08 Jan",
					"09 Jan",
					"10 Jan",
					"11 Jan",
					"12 Jan",
					"13 Jan",
					"14 Jan",
					"15 Jan",
					"16 Jan",
					"17 Jan",
					"18 Jan",
					"19 Jan",
					"20 Jan",
					"21 Jan",
					"22 Jan",
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: false,
					style: {
						colors: "#8695AA",
						fontSize: "12px",
						fontFamily: 'Inter',
					}
				}
			},
			yaxis: {
				tickAmount: 5,
				show: false,
				max: 250,
				min: 0,
				labels: {
					show: true,
					style: {
						colors: "#64748B",
						fontSize: "12px",
						fontFamily: 'Inter',
					},
				},
				axisBorder: {
					show: true,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: true,
					color: '#ECEEF2'
				}
			},
			grid: {
				show: false,
				borderColor: "#ECEEF2"
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'center',
				fontFamily: "Inter",
				fontWeight: 400,
				itemMargin: {
					horizontal: 8,
					vertical: 0
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			},
			tooltip: {
				enabled: true,
				y: {
					formatter: (val) => {
						return '$' + val / 1 + ''
					},
				}
			},
		};
		var chart = new ApexCharts(document.querySelector("#todays_earnings"), options);
		chart.render();
	}

	// Listener Analytics JS
	const getListenerAnalyticsId = document.getElementById('listener_analytics');
	if (getListenerAnalyticsId) {
		var options = {
			series: [
				{
					name: "Men",
					data: [50, 30, 40, 35, 30,]
				},
				{
					name: "Woman",
					data: [25, 40, 30, 45, 25,]
				},
			],
			chart: {
				type: "bar",
				height: 376,
				toolbar: {
					show: false
				}
			},
			colors: [
				"#605DFF", "#9CAAFF",
			],
			plotOptions: {
				bar: {
					columnWidth: "32%",
					borderRadius: 6,
					borderRadiusApplication: "end",
				}
			},
			fill: {
				type: ['gradient', 'solid'], // Apply gradient only to the first series
				gradient: {
					shade: 'light',
					type: 'vertical',
					shadeIntensity: 0.5,
					gradientToColors: ["#9747FF"], // End color for the gradient
					inverseColors: false,
					opacityFrom: 1,
					opacityTo: 1,
					stops: [0, 100]
				}
			},
			grid: {
				borderColor: '#ECEEF2',
				strokeDashArray: 8,
				xaxis: {
					lines: {
						show: false,
					}
				},
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				width: 3,
				show: true,
				colors: ["transparent"]
			},
			xaxis: {
				categories: [
					"18-24",
					"25-34",
					"35-44",
					"45-65",
					"65+",
				],
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				labels: {
					show: true,
					style: {
						colors: "#8695AA",
						fontSize: "12px",
						fontFamily: 'Inter',
					}
				}
			},
			yaxis: {
				tickAmount: 3,
				max: 60,
				min: 0,
				labels: {
					style: {
						colors: "#64748B",
						fontSize: "12px",
						fontFamily: 'Inter',
					},
					formatter: (val) => {
						return '' + val / 1 + '%'
					}
				},
				axisBorder: {
					show: false,
					color: '#ECEEF2'
				},
				axisTicks: {
					show: false,
					color: '#ECEEF2'
				}
			},
			legend: {
				show: true,
				position: 'top',
				fontSize: '12px',
				horizontalAlign: 'left',
				fontFamily: "Inter",
				fontWeight: 400,
				offsetY: 10,
				itemMargin: {
					horizontal: 8,
					vertical: 60
				},
				labels: {
					colors: '#3A4252'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			},
		};
		var chart = new ApexCharts(document.querySelector("#listener_analytics"), options);
		chart.render();
	}

	/* Listener Analytics 2 JS*/
	const getSalesByGender2Id = document.getElementById('sales_by_gender_2');
	if (getSalesByGender2Id) {
		var options = {
			series: [70, 30],
			chart: {
				height: 60,
				type: "donut"
			},
			labels: [
				"Men", "Woman",
			],
			colors: [
				"#605DFF", "#AD63F6"
			],
			stroke: {
				width: 0,
				show: true,
				colors: ["#ffffff"],
				lineCap: 'round'
			},
			legend: {
				show: false,
				position: 'right',
				fontSize: '12px',
				horizontalAlign: 'center',
				fontFamily: "Inter",
				fontWeight: 400,
				offsetY: 10,
				itemMargin: {
					horizontal: 0,
					vertical: 5
				},
				labels: {
					colors: '#64748B'
				},
				markers: {
					width: 9,
					height: 9,
					offsetX: -2,
					offsetY: -.5,
					radius: 2,
					shape: "diamond",
				}
			},
			plotOptions: {
				pie: {
					expandOnClick: false,
					donut: {
						size: '80%',
						labels: {
							show: false,
							name: {
								color: '#64748B'
							},
							value: {
								show: false,
								color: '#000',
								fontSize: '36px',
								fontWeight: '700',
								fontFamily: 'Inter',
								offsetY: 10,
								formatter: (val) => {
									return '' + val / 1 + '%'
								},
							},
							total: {
								show: false,
								color: '#64748B',
								fontSize: '14px',
								fontWeight: '400',
								fontFamily: 'Inter',
							}
						}
					}
				}
			},
			dataLabels: {
				enabled: false
			},
			tooltip: {
				enabled: true,
				y: {
					formatter: (val) => {
						return '' + val / 1 + '%'
					},
				}
			}
		}
		var chart = new ApexCharts(document.querySelector("#sales_by_gender_2"), options);
		chart.render();
	}


	


















	//**<---- Others All Charts ---->**//


	// Product Trends by Month JS
	const getProductTrendsByMonthId = document.getElementById('product_trends_by_month');
	if (getProductTrendsByMonthId) {
		var options = {
			series: [{
				name: "Desktops",
				data: [10, 41, 35, 51, 49, 62, 69, 91, 148]
			  }],
			chart: {
				height: 350,
				type: 'line',
				zoom: {
					enabled: false
				}
			  },
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: 'straight'
			},
			title: {
				text: 'Product Trends by Month',
				align: 'left',
				style: {
					fontSize:  '14px',
					fontWeight:  '500',
					color:  '#64748B'
				},
			},
			grid: {
				row: {
					colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
					opacity: 0.5
				},
			},
			xaxis: {
				categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
			}
		};
		var chart = new ApexCharts(document.querySelector("#product_trends_by_month"), options);
		chart.render();
	}
    
	// Average High & Low Temperature JS
	const getAverageHighLowTemperatureId = document.getElementById('average_high_low_temperature');
	if (getAverageHighLowTemperatureId) {
		var options = {
			series: [
				{
					name: "High - 2013",
					data: [28, 29, 33, 36, 32, 32, 33]
				},
				{
					name: "Low - 2013",
					data: [12, 11, 14, 18, 17, 13, 13]
				}
		  	],
			chart: {
				height: 350,
				type: 'line',
				dropShadow: {
					enabled: true,
					color: '#000',
					top: 18,
					left: 7,
					blur: 10,
					opacity: 0.2
				},
				zoom: {
					enabled: false
				},
				toolbar: {
					show: false
				}
		  	},
			colors: ['#77B6EA', '#545454'],
			dataLabels: {
				enabled: true,
			},
			stroke: {
				curve: 'smooth'
			},
			title: {
				text: 'Average High & Low Temperature',
				align: 'left',
				style: {
					fontSize:  '14px',
					fontWeight:  '500',
					color:  '#64748B'
				},
			},
			grid: {
				borderColor: '#e7e7e7',
				row: {
					colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
					opacity: 0.5
				},
			},
			markers: {
				size: 1
			},
			xaxis: {
				categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
				title: {
					text: 'Month'
				}
			},
			yaxis: {
				title: {
					text: 'Temperature'
				},
				min: 5,
				max: 40
			},
			legend: {
				position: 'top',
				horizontalAlign: 'right',
				floating: true,
				offsetY: -25,
				offsetX: -5,
				fontFamily: "Inter",
				fontWeight: 400,
			}
		};
		var chart = new ApexCharts(document.querySelector("#average_high_low_temperature"), options);
		chart.render();
	}

	//  Stepline Chart JS
	const getSteplineChartId = document.getElementById('stepline_chart');
	if (getSteplineChartId) {
		var options = {
			series: [{
				data: [34, 44, 54, 21, 12, 43, 33, 23, 66, 66, 58]
			}],
			chart: {
				type: 'line',
				height: 350
			},
			stroke: {
				curve: 'stepline',
			},
			dataLabels: {
				enabled: false
			},
			title: {
				text: 'Stepline Chart',
				align: 'left',
				style: {
					fontSize: '14px',
					fontWeight: '500',
					color: '#64748B'
				},
			},
			markers: {
				hover: {
					sizeOffset: 4
				}
			}
		};
		var chart = new ApexCharts(document.querySelector("#stepline_chart"), options);
		chart.render();
	}

	//  Page Statistics JS
	const getPageStatisticsId = document.getElementById('page_statistics');
	if (getPageStatisticsId) {
		var options = {
			series: [{
				name: "Session Duration",
				data: [45, 52, 38, 24, 33, 26, 21, 20, 6, 8, 15, 10]
			},
			{
				name: "Page Views",
				data: [35, 41, 62, 42, 13, 18, 29, 37, 36, 51, 32, 35]
			},
			{
				name: 'Total Visits',
				data: [87, 57, 74, 99, 75, 38, 62, 47, 82, 56, 45, 47]
			}
			],
			chart: {
				height: 350,
				type: 'line',
				zoom: {
					enabled: false
				},
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				width: [5, 7, 5],
				curve: 'straight',
				dashArray: [0, 8, 5]
			},
			title: {
				text: 'Page Statistics',
				align: 'left',
				style: {
					fontSize: '14px',
					fontWeight: '500',
					color: '#64748B'
				},
			},
			legend: {
				tooltipHoverFormatter: function (val, opts) {
					return val + ' - <strong>' + opts.w.globals.series[opts.seriesIndex][opts.dataPointIndex] + '</strong>'
				}
			},
			markers: {
				size: 0,
				hover: {
					sizeOffset: 6
				}
			},
			xaxis: {
				categories: ['01 Jan', '02 Jan', '03 Jan', '04 Jan', '05 Jan', '06 Jan', '07 Jan', '08 Jan', '09 Jan',
					'10 Jan', '11 Jan', '12 Jan'
				],
			},
			tooltip: {
				y: [
					{
						title: {
							formatter: function (val) {
								return val + " (mins)"
							}
						}
					},
					{
						title: {
							formatter: function (val) {
								return val + " per session"
							}
						}
					},
					{
						title: {
							formatter: function (val) {
								return val;
							}
						}
					}
				]
			},
			grid: {
				borderColor: '#f1f1f1',
			}
		};
		var chart = new ApexCharts(document.querySelector("#page_statistics"), options);
		chart.render();
	}

	// Spline JS
	const getSplineAreaId = document.getElementById('spline_area');
	if (getSplineAreaId) {
		var options = {
			series: [{
			name: 'series1',
			data: [31, 40, 28, 51, 42, 109, 100]
		  }, {
			name: 'series2',
			data: [11, 32, 45, 32, 34, 52, 41]
		  }],
			chart: {
			height: 350,
			type: 'area'
		  },
		  dataLabels: {
			enabled: false
		  },
		  stroke: {
			curve: 'smooth'
		  },
		  xaxis: {
			type: 'datetime',
			categories: ["2018-09-19T00:00:00.000Z", "2018-09-19T01:30:00.000Z", "2018-09-19T02:30:00.000Z", "2018-09-19T03:30:00.000Z", "2018-09-19T04:30:00.000Z", "2018-09-19T05:30:00.000Z", "2018-09-19T06:30:00.000Z"]
		  },
		  tooltip: {
			x: {
			  format: 'dd/MM/yy HH:mm'
			},
		  },
		  };
  
		  var chart = new ApexCharts(document.querySelector("#spline_area"), options);
		  chart.render();
	}

	// Area with Negative Values JS
	const getAreawithNegativeValuesId = document.getElementById('area_with_negative_values');
	if (getAreawithNegativeValuesId) {
		var options= {
			series: [ {

				name: 'north',
				data: [ {
					x: 1996,
						y: 322
				}

				,
				{
				x: 1997,
					y: 324
			}

			,
			{
			x: 1998,
				y: 329
		}

		,
		{
		x: 1999,
			y: 342
		}

		,
		{
		x: 2000,
			y: 348
		}

		,
		{
		x: 2001,
			y: 334
		}

		,
		{
		x: 2002,
			y: 325
		}

		,
		{
		x: 2003,
			y: 316
		}

		,
		{
		x: 2004,
			y: 318
		}

		,
		{
		x: 2005,
			y: 330
		}

		,
		{
		x: 2006,
			y: 355
		}

		,
		{
		x: 2007,
			y: 366
		}

		,
		{
		x: 2008,
			y: 337
		}

		,
		{
		x: 2009,
			y: 352
		}

		,
		{
		x: 2010,
			y: 377
		}

		,
		{
		x: 2011,
			y: 383
		}

		,
		{
		x: 2012,
			y: 344
		}

		,
		{
		x: 2013,
			y: 366
		}

		,
		{
		x: 2014,
			y: 389
		}

		,
		{
		x: 2015,
			y: 334
		}

		]
		}

		,
		{

		name: 'south',
		data: [ {
			x: 1996,
				y: 162
		}

		,
		{
		x: 1997,
			y: 90
		}

		,
		{
		x: 1998,
			y: 50
		}

		,
		{
		x: 1999,
			y: 77
		}

		,
		{
		x: 2000,
			y: 35
		}

		,
		{
		x: 2001,
			y: -45
		}

		,
		{
		x: 2002,
			y: -88
		}

		,
		{
		x: 2003,
			y: -120
		}

		,
		{
		x: 2004,
			y: -156
		}

		,
		{
		x: 2005,
			y: -123
		}

		,
		{
		x: 2006,
			y: -88
		}

		,
		{
		x: 2007,
			y: -66
		}

		,
		{
		x: 2008,
			y: -45
		}

		,
		{
		x: 2009,
			y: -29
		}

		,
		{
		x: 2010,
			y: -45
		}

		,
		{
		x: 2011,
			y: -88
		}

		,
		{
		x: 2012,
			y: -132
		}

		,
		{
		x: 2013,
			y: -146
		}

		,
		{
		x: 2014,
			y: -169
		}

		,
		{
		x: 2015,
			y: -184
		}

		]
		}

		],
		chart: {
			type: 'area',
				height: 350
		}

		,
		dataLabels: {
			enabled: false
		}

		,
		stroke: {
			curve: 'straight'
		}

		,

		title: {

			text: 'Area with Negative Values',
			align: 'left',
			style: {
				fontSize: '14px'
			}
		}

		,
		xaxis: {

			type: 'datetime',
			axisBorder: {
				show: false
			}

			,
			axisTicks: {
				show: false
			}
		}

		,
		yaxis: {
			tickAmount: 4,
			floating: false,

			labels: {
				style: {
					colors: '#8e8da4',
				}

				,
				offsetY: -7,
				offsetX: 0,
			}

			,
			axisBorder: {
				show: false,
			}

			,
			axisTicks: {
				show: false
			}
		}

		,
		fill: {
			opacity: 0.5
		}

		,
		tooltip: {
			x: {
				format: "yyyy",
			}

			,
			fixed: {
				enabled: false,
					position: 'topRight'
			}
		}

		,
		grid: {
			yaxis: {
				lines: {
					offsetX: -30
				}
			}

			,
			padding: {
				left: 20
			}
		}
		};
		var chart=new ApexCharts(document.querySelector("#area_with_negative_values"), options);
		chart.render();
	}

	//  Basic bar JS
	const getBasicBarId = document.getElementById('basic_bar');
	if (getBasicBarId) {
		var options = {
			series: [{
			  	data: [400, 430, 448, 470, 540, 580, 690, 1100, 1200, 1380]
			}],
			chart: {
				type: 'bar',
				height: 430
			},
			plotOptions: {
				bar: {
					borderRadius: 4,
					borderRadiusApplication: 'end',
					horizontal: true,
				}
			},
			dataLabels: {
			  	enabled: false
			},
			xaxis: {
				categories: ['South Korea', 'Canada', 'United Kingdom', 'Netherlands', 'Italy', 'France', 'Japan',
					'United States', 'China', 'Germany'
				],
			}
		};
		var chart = new ApexCharts(document.querySelector("#basic_bar"), options);
        chart.render();
	}

	// Grouped bar JS
	const getGroupedId = document.getElementById('grouped');
	if (getGroupedId) {
		var options = {
		series: [{
			data: [44, 55, 41, 64, 22, 43, 21]
		}, {
			data: [53, 32, 33, 52, 13, 44, 32]
		}],
		chart: {
			type: 'bar',
			height: 430
		},
		plotOptions: {
			bar: {
			horizontal: true,
			dataLabels: {
				position: 'top',
			},
			}
		},
		dataLabels: {
			enabled: true,
			offsetX: -6,
			style: {
			fontSize: '12px',
			colors: ['#fff']
			}
		},
		stroke: {
			show: true,
			width: 1,
			colors: ['#fff']
		},
		tooltip: {
			shared: true,
			intersect: false
		},
		xaxis: {
			categories: [2001, 2002, 2003, 2004, 2005, 2006, 2007],
		},
		};

		var chart = new ApexCharts(document.querySelector("#grouped"), options);
		chart.render();
	}

	// Grouped bar JS
	const getColumnId = document.getElementById('basic_column');
	if (getColumnId) {
		var options = {
			series: [{
			  name: 'Net Profit',
			  data: [44, 55, 57, 56, 61, 58, 63, 60, 66]
			}, {
			  name: 'Revenue',
			  data: [76, 85, 101, 98, 87, 105, 91, 114, 94]
			}, {
			  name: 'Free Cash Flow',
			  data: [35, 41, 36, 26, 45, 48, 52, 53, 41]
			}],
			chart: {
			  type: 'bar',
			  height: 350
			},
			plotOptions: {
			  bar: {
				horizontal: false,
				columnWidth: '55%',
				endingShape: 'rounded'
			  },
			},
			dataLabels: {
			  enabled: false
			},
			stroke: {
			  show: true,
			  width: 2,
			  colors: ['transparent']
			},
			xaxis: {
			  categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
			},
			yaxis: {
			  title: {
				text: '$ (thousands)'
			  }
			},
			fill: {
			  opacity: 1
			},
			tooltip: {
			  y: {
				formatter: function (val) {
				  return "$ " + val + " thousands"
				}
			  }
			}
		};
		  
		var chart = new ApexCharts(document.querySelector("#basic_column"), options);
		chart.render();
	}

	// Column with Data Labels JS
	const getColumnWithDataLabelsId = document.getElementById('column_with_data_labels');
	if (getColumnWithDataLabelsId) {
		var options = {
			series: [{
			  name: 'Inflation',
			  data: [2.3, 3.1, 4.0, 10.1, 4.0, 3.6, 3.2, 2.3, 1.4, 0.8, 0.5, 0.2]
			}],
			chart: {
			  height: 350,
			  type: 'bar',
			},
			plotOptions: {
			  bar: {
				borderRadius: 10,
				dataLabels: {
				  position: 'top', // top, center, bottom
				},
			  }
			},
			dataLabels: {
			  enabled: true,
			  formatter: function (val) {
				return val + "%";
			  },
			  offsetY: -20,
			  style: {
				fontSize: '12px',
				colors: ["#304758"]
			  }
			},
		  
			xaxis: {
			  categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
			  position: 'top',
			  axisBorder: {
				show: false
			  },
			  axisTicks: {
				show: false
			  },
			  crosshairs: {
				fill: {
				  type: 'gradient',
				  gradient: {
					colorFrom: '#D8E3F0',
					colorTo: '#BED1E6',
					stops: [0, 100],
					opacityFrom: 0.4,
					opacityTo: 0.5,
				  }
				}
			  },
			  tooltip: {
				enabled: true,
			  }
			},
			yaxis: {
			  axisBorder: {
				show: false
			  },
			  axisTicks: {
				show: false,
			  },
			  labels: {
				show: false,
				formatter: function (val) {
				  return val + "%";
				}
			  }
		  
			},
			title: {
			  text: 'Monthly Inflation in Argentina, 2002',
			  floating: true,
			  offsetY: 330,
			  align: 'center',
			  style: {
				color: '#444'
			  }
			}
		};
		  
		var chart = new ApexCharts(document.querySelector("#column_with_data_labels"), options);
		chart.render();
	}

})();