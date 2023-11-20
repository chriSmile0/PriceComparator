#ifndef UTIL_H
#define UTIL_H

#include <stdio.h>
#include <string.h>
#include <unistd.h>
#include <fcntl.h>
#include <stdlib.h>
#include <math.h>

#define PIH 3.14159265359
#define R 6371.0 // en km
/*************Dimension de la terre***********/

/**
 * Rayon : 6 371 km
 * Superficie : 148,9 millions km²
 * Superficie : 510,1 millions km²
 * Circonférence en x : 40075 km
 * Circonférence en y : 40000 km 
 * Méridien 0 : 
 * Équateur
*/

/**
 * @brief Calcul de la distance entre 2 points sur Terre -> méthode de haversine
 * @param{R} -> Le rayon 
 * @param{x1,y1} -> La longitude et la latitude du point1 
 * @param{x2,y2} -> La longitude et la latitude du point2 
 * @return -> La distance qui se calcul via : 
 * 				2*R*ArcSin(sqrt(sin²(((x2-x1)/2)+(cos(x1)*cos(x2)*sin²((y2-y1)/2)))) 
 * @return v2 -> 
 * 				A = sin²((x2-x1)/2)+(cos(x1)*cos(x2)*sin²((y2-y1)/2))
 * 				square = sqrt(A)/sqrt(1-A)
 * 				final = R*2*arctan(square)
 * @return v3 -> méthodes des sinus : 
 * 				R*arccos(sin(x1)*sin(x2)+cos(x1)*cos(x2)*cos(y2-y1))
 * @return v4 -> pythagore :
 * 				x = (x2-x1)*cos((y1+y2)/2)
 * 				y = y2-y1
 * 				z = sqrt(x²+y²)
 * 				final = k * z  avec k = 1.852*60

*/


/*
float x_1 = 1.0;
float x_2 = 2.0;
float y_1 = 4.0;
float y_2 = 5.0;
int R = 6371000; // en m
int new_two = 2;
float D = new_two*R*asin(sqrt((sin((x_2-x_1)/2))+(cos(x_1)*cos(x_2)*sin((y_2-y_1)/2))));

int carre = 2;
int double_carre = 2<<1;*/

/**********************FIN***************************/

/**
 * @brief
 * @param
 * @return 
*/
float pow_hf(float number, int puissance);

/**
 * @brief
 * @param
 * @return 
*/
int pow_hi(int number, int puissance);



typedef struct {
	float y;
	float x;
} coordinates;

typedef struct {
	int radius;
	coordinates tgt;
	float coordinates_max_x;
	float coordinates_max_y;
	float coordinates_min_x;
	float coordinates_min_y;
} circle_maxs;

/**
 * @brief
 * @param
 * @return 
*/
void print_coordinates(coordinates p);

/**
 * @brief
 * @param
 * @return 
*/
float calc_D_Earth2Points(coordinates p1, coordinates p2);

/**
 * @brief
 * @param
 * @return 
*/
float calc_Angle_Earth2Points(coordinates p1, coordinates p2);

/**
 * @brief
 * @param
 * @return 
*/
float ToNorth(coordinates c, float distance);

/**
 * @brief
 * @param
 * @return 
*/
float ToEast(coordinates c, float distance);

/**
 * @brief
 * @param
 * @return 
*/
float ToSouth(coordinates c, float distance);

/**
 * @brief
 * @param
 * @return 
*/
float ToWest(coordinates c, float distance);

/**
 * @brief
 * @param
 * @return 
*/
coordinates ToAnyDegree(coordinates p1, float distance , int degres);


#endif // UTIL_H // 