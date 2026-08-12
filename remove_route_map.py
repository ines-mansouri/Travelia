import os

# Remove the flight-route-map component
component_path = "resources/views/components/flight-route-map.blade.php"
if os.path.exists(component_path):
    print(f"Removing component file: {component_path}")
    os.remove(component_path)
else:
    print(f"Component file not found: {component_path}")

# Remove flight route map functionality from flights.blade.php
main_view_path = "resources/views/flights.blade.php"
if os.path.exists(main_view_path):
    with open(main_view_path, "r") as f:
        content = f.read()
    
    # Remove flight route map section - find markers and remove the section
    markers_to_remove = [
        "/* Flight Route Map */",
        "// ── Flight Route Map Rendering",
        "// Flight Route Map",
        "Route Visualisation",
        "Flight Route Map",
        "tt-flight-map",
        "renderFlightMap",
        "traveliaMap",
    ]
    
    removed_count = 0
    for marker in markers_to_remove:
        if marker in content:
            content = content.replace(marker, "")
            removed_count += 1
            print(f"Removed marker: {marker}")
    
    # Remove empty lines that were left behind
    lines = content.splitlines(keepends=True)
    filtered_lines = []
    for i, line in enumerate(lines):
        if line.strip() == "" and i > 0 and i < len(lines) - 1:
            # Check if previous and next lines are also empty
            if i > 0 and lines[i-1].strip() == "" and i < len(lines) - 1 and lines[i+1].strip() == "":
                continue
        filtered_lines.append(line)
    
    new_content = "".join(filtered_lines)
    
    # Write back
    with open(main_view_path, "w") as f:
        f.write(new_content)
    print(f"Removed flight route map functionality from: {main_view_path}")
else:
    print(f"Main view file not found: {main_view_path}")

print("Flight route map functionality removed successfully")