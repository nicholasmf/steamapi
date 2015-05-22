function barChart(canvas, array)
{
	var array;
	var context;
	var cHeight, cWidth;
	var cMargin, cSpace, cMarginSpace, cMarginHeight;
	var totalBars, totLabelsOnXAxis, bHeight;
	var maxDataValue;
	var ctr, numctr;

	this.canvas = canvas;
	if (this.canvas && this.canvas.getContext)
		this.context = canvas.getContext('2d');
	else
	{
		alert("invalid canvas: " + this.canvas);
		return;
	}

	this.array = array;
	if (!this.array)
	{
		alert("Not an array");
		return;
	}
	else if (this.array.length < 1)
	{
		alert("Array empty");
		return;
	}

	this.setParameters();
	this.drawAxisLabelMarkers();
	this.drawChartWithAnimation();
	//console.log(this.totalBars);
}

barChart.prototype.setParameters = function ()
{
	this.cMargin = 25;
	this.cSpace = 60;
	this.cHeight = this.canvas.height - 2*this.cMargin - this.cSpace;
	this.cWidth = this.canvas.width - 2*this.cMargin - this.cSpace;
	this.cMarginSpace = this.cMargin + this.cSpace;
	this.cMarginHeight = this.cMargin + this.cHeight;

	this.totalBars = this.array.length;
	this.bHeight = (this.cWidth/this.totalBars) - this.bMargin;

	this.maxDataValue = 0;
	for (i = 0; i < this.totalBars; i++)
	{
		var arrVal = this.array[i].split(",");
		var val = parseInt(arrVal[1]);
		if (parseInt(val) > parseInt(this.maxDataValue))
			this.maxDataValue = val;
	}
	this.totLabelsOnXAxis = 10;
	this.context.font = "10pt Garamond";

	// animation vars
	this.ctr = 0;
	this.numctr = 100;
	this.speed = 10;

	//console.log(this.maxDataValue);
}

barChart.prototype.setPosition = function (x, y) 
{
	this.canvas.style.left = x;
	this.canvas.style.top = y;
}

barChart.prototype.drawRect = function (x, y, w, h)
{
	this.context.beginPath();
	this.context.rect(x, y, w, h);
	this.context.closePath();
	this.context.stroke();
}

// draw chart axis, labels and markers
barChart.prototype.drawAxisLabelMarkers = function ()
{
	this.context.lineWidth = "2.0";
	// draw Y axis
	this.drawAxis(this.cMarginSpace, this.cMarginHeight, this.cMarginSpace, this.cMargin);
	// draw X axis
	this.drawAxis(this.cMarginSpace, this.cMarginHeight, this.cMarginSpace + this.cWidth, this.cMarginHeight);
	this.context.lineWidth = "1.0";
	this.drawMarkers();
}

// draw X and Y axis
barChart.prototype.drawAxis = function (x, y, X, Y)
{
	this.context.beginPath();
	this.context.moveTo(x, y);
	this.context.lineTo(X, Y);
	this.context.closePath();
	this.context.stroke();            
}

// draw chart markers on X and Y axis
barChart.prototype.drawMarkers = function ()
{
	var numMarkers = parseInt(this.maxDataValue / this.totLabelsOnYAxis);
	this.context.textAlign = "right";
	this.context.fillStyle = "#000";

	// Y axis
	for (var i = 0; i <= this.totLabelsOnYAxis; i++)
	{
		var markerVal = i * numMarkers;
		var markerValHt = i * numMarkers * this.cWidth;
		var xMarkers = this.cMarginSpace + (markerValHt / this.maxDataValue);
		var yMarkers = this.cMarginHeight + 10;
		this.context.fillText(markerVal, xMarkers, yMarkers, this.cSpace);
	}

	// X axis
	this.context.textAlign = "center";
	for (var i = 0; i < this.totalBars; i++)
	{
		var arrval = this.array[i].split(",");
		var name = arrval[0];

		var markerXPos = this.cMarginSpace - 20;
		var markerYPos = this.cMarginHeight - (i * (this.bHeight+this.bMargin)) - (this.bHeight / 2);
		this.context.fillText(name, markerXPos, markerYPos, this.bWidth);
	}

	//context.fillText("Test", cWidth + cMarginSpace, cMarginHeight);

	this.context.save();

	// Add Y axis title
	this.context.translate(this.cMargin + 10, this.cHeight / 2);
	this.context.rotate(Math.PI * -90/180);
	this.context.fillText('Year Wise', 0, 0);

	this.context.restore();

	// add X axis title
	this.context.fillText("Visitors in Thousands", this.cMarginSpace + (this.cWidth / 2), this.cMarginHeight + 30);
}

barChart.prototype.drawChartWithAnimation = function drawChart()
{
	// Clear graphic area
	this.context.save();
	this.context.beginPath();
	this.context.rect(this.cMarginSpace + 1, 0, this.cWidth+this.cMargin, this.cHeight+this.cMargin - 2);
	this.context.closePath();
	this.context.fillStyle = "white";
	this.context.fill();
	this.context.restore();

	// Loop through the total vars and draw
	for (var i = 0; i < this.totalBars; i++)
	{
		var arrVal = this.array[i].split(",");
		var bVal = parseInt(arrVal[1]);
		var bHt = (bVal * this.cWidth / this.maxDataValue) / this.numctr * this.ctr;
		var bX = this.cMarginSpace + bHt - 2;
		var bY = this.cMarginHeight - (i * (this.bHeight+this.bMargin)) - this.bMargin - (this.bHeight);
		this.drawRectangle(this.cMarginSpace + 2, bY, bX - this.cMarginSpace, this.bHeight, true);

		// write value
		this.context.save();
		this.context.strokeStyle = "black";
		this.context.fillStyle = "black";				
		this.context.fillText (bVal, bX+15, bY+10);
		this.context.restore();
	}
	// timeout runs and checks if bars have reached the desired height
	// if not, keep growing
	if (this.ctr < this.numctr)
	{
		this.ctr = this.ctr + 1;
		setTimeout(drawChart, this.speed);
	}
}

barChart.prototype.drawRectangle = function (x, y, w, h, fill)
{
	this.context.beginPath();
	this.context.rect(x, y, w, h);
	this.context.closePath();
	this.context.stroke();

	if (fill)
	{
		var gradient = this.context.createLinearGradient(500, 0, this.cMarginSpace, 0);
		gradient.addColorStop(0, 'green');
		gradient.addColorStop(1, 'rgba(67,203,36,.15)');
		this.context.fillStyle = gradient;
		this.context.strokeStyle = gradient;
		this.context.fill();
	}
}

function createCanvas(id, width, height)
{
	var tempCanvas = document.createElement('canvas');
	tempCanvas.id = id;
	tempCanvas.width = width;
	tempCanvas.height = height;
	tempCanvas.style.zIndex =1;
	tempCanvas.style.position = "absolute";

	return tempCanvas;				
}


