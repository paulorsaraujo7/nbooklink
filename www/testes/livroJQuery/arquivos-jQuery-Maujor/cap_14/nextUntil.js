$.fn.nextUntil = function(expr) {
    var match = [];

    if ( expr.jquery )
        expr = expr[0];

    // We need to figure out which elements to push onto the array
    this.each(function(){
        // Traverse through the sibling nodes
        for( var i = this.nextSibling; i; i = i.nextSibling ) {
            // Make sure that we're only dealing with elements
            if ( i.nodeType != 1 ) continue;

            // If we find a match then we need to stop
            if ( expr.nodeType ) {
                if ( i == expr ) break;
            } else if ( jQuery.multiFilter( expr, [i] ).length ) break;

            // Otherwise, add it on to the stack
            match.push( i );
        }
    });

    return this.pushStack( match, arguments );
};

