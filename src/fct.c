#include "../inc/fct.h"


float translate_distance_add_to_coordinates_in_coordinates(int distance,float old_coordinate) {
	(void) distance;
	(void) old_coordinate;
	return 0.0;
}

void init_circle_maxs(circle_maxs *to_init, int radius,coordinates cds_tgt) {
	to_init->radius = radius;
	to_init->tgt = cds_tgt;
	to_init->coordinates_max_x = to_init->tgt.x + radius;
	to_init->coordinates_min_x = to_init->tgt.x - radius;
	to_init->coordinates_max_y = to_init->tgt.y + radius;
	to_init->coordinates_min_y = to_init->tgt.y - radius;
}