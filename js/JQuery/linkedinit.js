// JavaScript Document
jQuery(document).ready(function($) {
$('#county').linkedSelect('/ajax/places/county','#district',{firstOption: 'Please select a district'});
$('#county').linkedSelect('/ajax/regions/county','#regionID',{firstOption: 'Please select the district or country',loadingText: 'Loading Please Wait...'});
$('#county').linkedSelect('/ajax/parishesbycounty/county','#parish',{firstOption: 'Please select a parish',loadingText: 'Loading Please Wait...'});
$('#district').linkedSelect('/ajax/parishes/district','#parish',{firstOption: 'Please select a parish', loadingText: 'Loading Please Wait...'});
$('#landusevalue').linkedSelect('/ajax/landusecodes/term/','#landusecode',{firstOption: 'Please select a landuse', loadingText: 'Loading Please Wait...'});

});
