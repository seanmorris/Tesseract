Abstract:

The tesseract class allows you to see your data from another "angle." The word
itself is used in mathematical circles for what is best described as "the analog
of a cube in hyperspace."

The class allows you to define an arbitrary amount of dimensions along which to
work with data. It is a multidimensional array that you can rotate like a cube
in your hand. In the example, an array of objects representing fighter jets is
created and inserted into the tesseract. The objects have wingspan,
year introduced, weight, length, and wingspan and numerical properties. These
will be plotted along the dimensions. The name property is also included for
clarity.

Once the dimensions are labeled and the data had been inserted the tesseract may
be rotated. In the example the weight, length and year introduced are rotated
with the original top dimension, wingspan. After this, the tesseract may be
flattened to a two dimensional array, a list of lists keyed by the new top
dimension.

Any modifications performed to values in a "rotated" Tesseract object will be
reflected in all other "rotated" tesseracts as well as the original. Remember,
these all represent different "angles" of the same data. Be mindful.

Usage:

	To create a new tesseract of 4 dimensions:
		$t = new Tesseract(4);

	To label the 4 dimensions:
		$t->label('Dim A', 'Dim B', 'Dim C', 'Dim D');

	To add some data to point(0,0,0,0) in the tesseract:
		$t->insert(0, 0, 0, 0, 'Data');

	To get the data at point(0,0,0,0)
		$t->get(0, 0, 0, 0);

	To delete the data at point(0, 0, 0, 0)
		$t->delete(0, 0, 0, 0);

	To rotate any dimension to the top dimension, pass the top dimensions label
	and 0 to the rotate function.
		$s = $t->rotate('Dim D', 0);

	To "flatten" the tesseract to an array:
		$a = $t->flatten();

	To add dimensions to a tesseract:
		$h = $t->lift('New Dimension Label');

	To resolve the dimensions cardinality from a label:
		$c = $t->resolve('Dim B');
