# reverse_rsh
Something like ssh but the other way around.

The story: my mother bought an iPad 1 for my grandma. Seriously.
Now I want to be able to do remote maintenance of that iPad.
But there's no reliable way to do port forwarding so I can reach the ipad.
The solution:
The ipad has to connect to my vps if wifi is on and the screen is unlocked.
And then I can login to a `screen` instance on my vps and type on a shell of the ipad.
That's the magic.

# The code is obvious.
