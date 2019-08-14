var track_parameter = {};
function commonTrackFun(PID,category,baseURL,event){
	
	track_parameter['event']		= event;
	track_parameter['sourceProdID']	= PID;
	track_parameter['sourceCatgID']	= category;
	track_parameter['vendor']		= baseURL;
	
	return track_parameter;
}

function caltrackevent(PID,category,baseURL,media_name){
	
	commonTrackFun(PID,category,baseURL,'socialShare');
	track_parameter['socialMedium']	= media_name;
		
	return track(track_parameter);
}

function carouselSwipe(PID,category,baseURL){
	commonTrackFun(PID,category,baseURL,'carouselSwipe');
	return track(track_parameter);
}

function carouselClick(sorcePID,sourceCategory,clickPID,clickCategory,baseURL,posOfRecord){
	
	commonTrackFun(sorcePID,sourceCategory,baseURL,'carouselClick');
	track_parameter['destProdID']	= clickPID;
	track_parameter['destCatgID']	= clickCategory;
	track_parameter['posOfReco']	= posOfRecord;
		
	return track(track_parameter);
}

function cartOrWishlist(event,PID,category,country,productPrice,baseURL,posOfRecord){
	commonTrackFun(PID,category,baseURL,event);
	track_parameter['country']		= country;
	track_parameter['prodPrice']	= productPrice;
	track_parameter['posOfReco']	= posOfRecord;
	
	return track(track_parameter);
	
}
