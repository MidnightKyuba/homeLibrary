function changeSizeText(value)
{
	var textSizeArray = ['xx-small','x-small','small','medium','large','x-large','xx-large'];
	$(".text").css("fontSize", textSizeArray[value]);
}