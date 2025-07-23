/**
 * @since       22.07.2025 - 08:30
 *
 * @author      Patrick Froch <info@netgroup.de>
 *
 * @see         http://www.netgroup.de
 *
 * @copyright   NetGroup GmbH 2025
 */
class HighlighterInitializer {

    initialize() {
        hljs.highlightAll()
    }
}


document.addEventListener('DOMContentLoaded', function(event) {
    const hli = new HighlighterInitializer()
    hli.initialize()
})