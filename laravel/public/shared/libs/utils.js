/**
 * dotAccess
 * Access the object using the dot notation.
 * When value is provided is a setter, otherwise it is a getter
 *
 * @param obj {Object}  - The object to access
 * @param is {string}  - The property names separated b dot
 */
function dotAccess(obj, is, value) {
    if (typeof is == 'string')
        return dotAccess(obj, is.split('.'), value);
    else if (is.length == 1 && value !== undefined)
        return obj[is[0]] = value;
    else if (is.length == 0)
        return obj;
    else {
        // Create nested property if not exists
        if (obj[is[0]] === undefined && value !== undefined) {
            obj[is[0]] = {};
        }
        return dotAccess(obj[is[0]], is.slice(1), value);
    }
};
