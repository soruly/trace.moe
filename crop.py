import cv2
import numpy as np
import sys

image = cv2.imread(sys.argv[1])
height, width, channels = image.shape
# Convert image to grayscale
imgray = cv2.cvtColor(image,cv2.COLOR_BGR2GRAY)

# Set threshold
#th1 = cv2.adaptiveThreshold(imgray,255,cv2.ADAPTIVE_THRESH_GAUSSIAN_C,cv2.THRESH_BINARY,1023,0)
_,th2 = cv2.threshold(imgray,8,255,cv2.THRESH_BINARY)
im2, contours, hierarchy = cv2.findContours(th2,cv2.RETR_EXTERNAL,cv2.CHAIN_APPROX_SIMPLE)

# Find with the largest rectangle
areas = [cv2.contourArea(contour) for contour in contours]
max_index = np.argmax(areas)
cnt = contours[max_index]
x,y,w,h = cv2.boundingRect(cnt)

# Ensure bounding rect should be at least 16:9 or taller
# For images that is not ~16:9
# And its detected bounding rect wider than 16:9
if abs(width / height - 16 / 9) < 0.03 and (w / h - 16 / 9) > 0.03:
  # increase top and bottom margin
  newHeight = w / 16 * 9
  y = y - (newHeight - h ) / 2
  h = newHeight

# ensure the image has dimension
y = 0 if y < 0 else y
x = 0 if x < 0 else x
w = 1 if w < 1 else w
h = 1 if h < 1 else h

# Crop with the largest rectangle
crop = image[y:y+h,x:x+w]
cv2.imwrite(sys.argv[2],crop)

exit()
# Draw preview image for debug
imagePreview = cv2.imread(sys.argv[1])
for contour in contours:
    #epsilon = 0.001 * cv2.arcLength(contour,True)
    #cv2.drawContours(imagePreview, [cv2.approxPolyDP(contour, epsilon, True)], 0, (0,255,0), 1)
    cv2.drawContours(imagePreview, [contour], 0, (0,255,0), 1)
cv2.imshow("adaptiveThreshold", th1)
cv2.imshow("globalThreshold", th2)
cv2.imshow("drawContours", imagePreview)
cv2.imshow("cropped", crop)
cv2.waitKey(0)
cv2.destroyAllWindows()
