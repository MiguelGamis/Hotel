var sampleData = [23, 43, 54, 94, 23, 54, 36, 96, 100, 250, 45];

function _div(id) {
	return document.getElementById(id);
}

function drawBarChart(dataset, idOfContainer) {
	// Grab the id of the container
	var chartContainer = _div(idOfContainer);
	
	if(typeof(dataset) != "object"){
		return;
	}
    
    //Grab the width of the container
    var widthOfContainer = chartContainer.scrollWidth;
       
    // Grab the height of the container 
    var heightOfContainer = chartContainer.scrollHeight;
       
    //Determine the width of each bar based on the number of data in the dataset
    var widthOfBar = parseInt(widthOfContainer / dataset.length) - 2;
    
    for(var i = 0; i < dataset.length; i++){
        //Create our div element
        var divElement = document.createElement("div");
        
        //Static attributes of the element
        divElement.setAttribute("class", "div2");
        
        //Dynamic attributes of the element
        divElement.style.marginLeft = parseInt(i * 2 + i * widthOfBar) + "px";
        divElement.style.height = parseInt(dataset[i]) + "px";
        divElement.style.width = parseInt(widthOfBar) + "px";
        divElement.style.top = (heightOfContainer - parseInt(dataset[i]) - 1) + "px";
        chartContainer.appendChild(divElement);
    }
    return false;
}

drawBarChart(sampleData, "div1");