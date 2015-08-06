/**
 * JavaScript for form editing publicprivate conditions.
 *
 * @module moodle-availability_publicprivate-form
 */
M.availability_publicprivate = M.availability_publicprivate || {};

/**
 * @class M.availability_publicprivate.form
 * @extends M.core_availability.plugin
 */
M.availability_publicprivate.form = Y.Object(M.core_availability.plugin);

M.availability_publicprivate.form.getNode = function(json) {
    // Create HTML structure.
    var html = M.util.get_string('label_publicprivate', 'availability_publicprivate') + ' <span class="availability-group"><label>' +
            '<span class="accesshide">' + M.util.get_string('label_publicprivate', 'availability_publicprivate') + ' </span>' +
            '<select name="status" title="' + M.util.get_string('label_publicprivate', 'availability_publicprivate') + '">' +
            '<option value="1">' + M.util.get_string('option_private', 'availability_publicprivate') + '</option>' +
            '<option value="0">' + M.util.get_string('option_public', 'availability_publicprivate') + '</option>' +
            '</select></label></span>';
    var node = Y.Node.create('<span>' + html + '</span>');

    // Set initial values.
    if (json.e !== undefined) {
        node.one('select[name=status]').set('value', '' + json.status);
    }

    // Add event handlers (first time only).
    if (!M.availability_publicprivate.form.addedEvents) {
        M.availability_publicprivate.form.addedEvents = true;
        var root = Y.one('#fitem_id_availabilityconditionsjson');
        root.delegate('change', function() {
            // Whichever dropdown changed, just update the form.
            M.core_availability.form.update();
        }, '.availability_publicprivate select');
    }

    return node;
};

M.availability_publicprivate.form.fillValue = function(value, node) {
    value.status = parseInt(node.one('select[name=status]').get('value'), 10);
};
