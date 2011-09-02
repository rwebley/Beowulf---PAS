// JavaScript Document
jQuery(document).ready(function($) {
 
$('#categoryID').linkedSelect('/ajax/medcatruler/','#ruler',{firstOption: 'Please select a ruler', loadingText: 'Loading Please Wait...'});
$('#ruler').linkedSelect('/ajax/medmintruler/','#mint_id',{firstOption: 'Please select a mint', loadingText: 'Loading Please Wait...'});
$('#ruler').linkedSelect('/ajax/earlymedtyperuler/','#typeID',{firstOption: 'Please select a type', loadingText: 'Loading Please Wait...'});
$('#ruler').linkedSelect('/ajax/rulerdenomearlymed/','#denomination',{firstOption: 'Please select a denomination', loadingText: 'Loading Please Wait...'});
});
