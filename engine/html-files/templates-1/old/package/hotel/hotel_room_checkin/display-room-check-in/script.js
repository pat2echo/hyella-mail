var nwGroupCheckIn = function () {
	return {
		init: function(){
			$('select.select-room-guest').select2();
		},
		refreshRoomGuestList: function(){
			$('select.select-room-guest').select2("destroy").change();
			$('select.select-room-guest').select2();
		}
	};
}();
nwGroupCheckIn.init();