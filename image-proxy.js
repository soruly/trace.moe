addEventListener("fetch", async (event) => {
  event.respondWith(handleRequest(event.request));
});

const errorResponse = (errorMessage) => new Response(errorMessage, {
  status: 400,
  statusText: "Bad Request",
  headers: {
    "access-control-allow-origin": "https://trace.moe",
    "access-control-allow-methods": "GET"
  }
});

const handleRequest = async (originalRequest) => {
  let originalURL = new URL(originalRequest.url);
  if (!originalURL.searchParams.get("url")) {
    return errorResponse("Error: Cannot get url from param");
  }

  let imageURL = null;
  try {
    imageURL = new URL(originalURL.searchParams.get("url"));
  } catch(e) {}
  if (!imageURL) {
    return errorResponse("Error: Invalid URL string");
  }

  let imageRequest = new Request(imageURL, {
    redirect: "follow",
    headers: {
      referer: imageURL.origin
    }
  });

  let response = await fetch(imageRequest, {
    cf: {
      polish: "lossy"
    }
  });

  if (response.status >= 400) {
    let headers = new Headers(response.headers);
    headers.append("access-control-allow-origin", "https://trace.moe");
    headers.append("access-control-allow-methods", "GET");
    return new Response(
      response.body,
      {
        status: response.status,
        statusText: response.statusText,
        headers
      }
    )
  }

  if (response.headers.get("Content-Type").split("/")[0].toLowerCase() !== "image") {
    return errorResponse("Error: Content-Type is not image");
  }

  let headers = new Headers(response.headers);
  headers.append("access-control-allow-origin", "https://trace.moe");
  headers.append("access-control-allow-methods", "GET");
  return new Response(
    response.body,
    {
      status: response.status,
      statusText: response.statusText,
      headers
    }
  )
}
