#include "../inc/util.h"

float pow_hf(float number, int puissance) {
	float res = number;
	for(int i = 1 ; i < puissance ; i++)
		res *= number;
	return res;
}

int pow_hi(int number, int puissance) {
	int res = number;
	for(int i = 1 ; i < puissance ; i++)
		res *= number;
	return res;
}

void print_coordinates(coordinates p) {
	printf("%f,%f", p.y, p.x);
}


float calc_D_Earth2Points(coordinates p1, coordinates p2) {
	float p1yr = p1.y * (PIH / 180);
	float p2yr = p2.y * (PIH / 180);
	float x = (p1.x - p2.x) * cos((p1yr + p2yr) / 2);
	float y = p2.y - p1.y;
	float z = sqrtf(pow_hf(x, 2) + pow_hf(y, 2));
	float k = 1.852 * 60;
	printf("x : %f , y : %f , z : %f\n", x, y, z);

	/**
	 * Équation à résoudre : 
	 * d = k * z ; k = 1.852*60 = 111.12
	 * estimons que l'on connaisse d. Par exemple 111
	 * on a donc 111 = 111 * z donc z = 1
	 * 	z = sqrt(x²+y²)
	 *  1 = ...
	 *  1² = x² + y²
	 *  1 - x² = y² 
	 *  On résout donc le système à 2 inconnu 
	 *  x² = 1-y²
	 *  y² = 1-x²
	 * ---------
	 * x² = 1-(1-x²) -> x² = 1-1+x² -> x² = x² 
	 * y² = x² donc -1<x,y<1
	 * 
	 * AVEC EQUATION COMPLÈTE 
	 * k*z = d 
	 * 2*4 = 8 -> 8/2 = 4 , 8/4 = 2
	 * z = d/k
	 * d*k = z = (p1.X-p2.x)*cos(((p1.y*PIH/180)+(p2.y*PIH/180))/2)
	 * z = (p1.x-p2.x)*cos(((p1.y*PIH/180)+(p2.y*PIH/180))/2)
	 * z/cos(((p1.y*PIH/180)+(p2.y*PIH/180))/2) = p1.x-p2.x
	 * z/cos(((p1.y*PIH/180)+(p2.y*PIH/180))/2) - p1.x = - p2.x
	 * -z/cos(((p1.y*PIH/180)+(p2.y*PIH/180))/2) + p1.x = p2.x
	 * 
	 * 
	 * 1/cos(((p1.y*PIH/180)+(p1.y*PIH/180))/2) = p1.x-p2.x
	 * 1/cos(((p1.y*PIH/180)+(p1.y*PIH/180))/2) - p1.x  = -p2.x
	 * -1/cos(((p1.y*PIH/180)+(p1.y*PIH/180))/2) + p1.x = p2.x
	*/
	return k * z;
}

float calc_Angle_Earth2Points(coordinates p1, coordinates p2) {
	(void) p1;
	(void) p2;
	return 0.0;
}


float ToNorth(coordinates c, float distance) {
	return c.y + (distance / R) * (180 / PIH);
}

float ToEast(coordinates c, float distance) {
	return c.x + (distance / R) * (180 / PIH) / cos(c.y * PIH / 180);
}

float ToSouth(coordinates c, float distance) {
	return c.y - (distance / R) * (180 / PIH);
}

float ToWest(coordinates c, float distance) {
	return c.x - (distance / R) * (180 / PIH) / cos(c.y * PIH / 180);
}


coordinates ToAnyDegree(coordinates c, float distance , int degres) {
	//degres va entre 0 et 360
	/**
	 * On suit la règle simple de la boussole qui va plein nord
	 * 0 = plein ouest
	 * 90 = plein nord
	 * 180 = plein est
	 * 270 = plein sud
	 * Autre = y -> sin , x -> cos
	*/
	coordinates new_cdt;
	new_cdt.y = c.y + (distance / R) * (180 / PIH) * sin(degres * PIH / 180);
	new_cdt.x = c.x + ((distance / R) * (180 / PIH) * 
						sin((degres-90) * PIH / 180) / cos(c.y * PIH / 180));
	return new_cdt;
}