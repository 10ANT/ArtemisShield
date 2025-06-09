(function() {
	"use strict";

	//**<---- Dashboard Finance ---->**//

	/* Cash at End of the Month JS */
	const getCashAtEndOfTheMonthId = document.getElementById('cash_at_end_of_the_month');
	if (getCashAtEndOfTheMonthId) {
		var chartDom = document.getElementById('cash_at_end_of_the_month');
		var myChart = echarts.init(chartDom);
		var option;

		// This example requires ECharts v5.5.0 or later
		option = {
			tooltip: {
				trigger: 'item'
			},
			legend: {
				bottom: '0',
				left: 'center',
				itemWidth: 10,
				itemHeight: 10,
				textStyle: {
					fontSize: 12,
					color: "#64748B",
					
				}
			},
			color: ["#37D80A", "#FD5812", "#605DFF"],
			series: [
				{
					name: 'Cash at End of the Month',
					type: 'pie',
					radius: ['40%', '70%'],
					center: ['50%', '70%'],
					// adjust the start and end angle
					startAngle: 180,
					endAngle: 360,
					data: [
						{ value: 1048, name: '42.9%' },
						{ value: 735, name: '20.0%' },
						{ value: 580, name: '37.1%' },
					]
				},
			]
		};

		option && myChart.setOption(option);
	}

	/* Income Source JS */
	const getIncomeSourceId = document.getElementById('income_source');
	if (getIncomeSourceId) {
		var chartDom = document.getElementById('income_source');
		var myChart = echarts.init(chartDom);
		var option;

		option = {
			legend: {
				bottom: '0',
				left: 'center',
				itemWidth: 10,
				itemHeight: 10,
				textStyle: {
					fontSize: 12,
					color: "#fff",
					
				}
			},
			toolbox: {
				show: false,
				feature: {
					mark: { show: true },
					dataView: { show: true, readOnly: false },
					restore: { show: true },
					saveAsImage: { show: true }
				}
			},
			color: ['#3584FC', '#FD5812', '#3584FC', '#FD5812' ],
			series: [
				{
					name: 'Income Source',
					type: 'pie',
					radius: [15, 70],
					center: ['50%', '50%'],
					roseType: 'area',
					itemStyle: {
						borderRadius: 8
					},
					data: [
						{ value: 40, name: 'Product Sales' },
						{ value: 15, name: 'Investments' },
						{ value: 35, name: 'Salary' },
						{ value: 20, name: 'Consulting' },
					]
				}
			]
		};

		option && myChart.setOption(option);
	}

	/* Expense Breakdown JS */
	const getExpenseBreakdownId = document.getElementById('expense_breakdown');
	if (getExpenseBreakdownId) {
		var chartDom = document.getElementById('expense_breakdown');
		var myChart = echarts.init(chartDom);
		var option;
		option = {
			tooltip: {
				trigger: 'item'
			},
			legend: {
				bottom: '0',
				left: 'center',
				itemWidth: 10,
				itemHeight: 10,
				textStyle: {
					fontSize: 12,
					color: "#64748B",
					
				}
			},
			color: ["#AD63F6", "#BF85FB", "#D7B5FD", "#E9D5FF"],
			series: [
				{
					name: 'Expense Breakdown',
					type: 'pie',
					radius: '50%',
					data: [
						{ value: 1048, name: 'Marketing' },
						{ value: 735, name: 'Sales' },
						{ value: 580, name: 'Salaries' },
						{ value: 484, name: 'Miscellaneous' },
					],
					emphasis: {
					itemStyle: {
						shadowBlur: 10,
						shadowOffsetX: 0,
						shadowColor: 'rgba(0, 0, 0, 0.5)'
						}
					}
				}
			]
		};

		option && myChart.setOption(option);
	}

})();