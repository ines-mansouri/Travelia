#!/usr/bin/env python3
"""
Script to remove Flight Route functionality from the Tours-Travel project
This script:
1. Removes the flight-route-map.blade.php component file
2. Cleans up the flights.blade.php view file
3. Removes all references to route visualization, maps, and tt-flight-map
"""

import os
import re

def remove_component_files():
    """Remove the flight-route-map component file"""
    component_path = "resources/views/components/flight-route-map.blade.php"
    if os.path.exists(component_path):
        print(f"Removing component file: {component_path}")
        os.remove(component_path)
        print("✓ Component file removed")
    else:
        print(f"Component file not found: {component_path}")

def clean_flights_view():
    """Clean up the flights.blade.php file"""
    main_view_path = "resources/views/flights.blade.php"
    if not os.path.exists(main_view_path):
        print(f"Main view file not found: {main_view_path}")
        return
    
    with open(main_view_path, "r") as f:
        content = f.read()
    
    initial_length = len(content)
    
    # Remove flight route map sections
    flight_map_sections = [
        r"/\* Flight Route Map \*/.*?(?=\n\n|// ── Price Calendar)",
        r"// ── Flight Route Map Rendering.*?// ── Price Calendar",
        r"// Flight Route Map.*?\n",
    ]
    
    # Remove route visualization text
    route_visualization_patterns = [
        r"Route Visualisation",
        r"Flight Route Map",
        r"Route <span class=\"accent\">Visualisation</span>",
        r"Route <span class=\"accent\">Visualisation</span></h2>",
        r"Route <span class=\"accent\">Visualisation</span></div>",
    ]
    
    # Remove map related IDs and functions
    map_patterns = [
        r"mapSection\.style\.display\s*=\s*'block';",
        r"renderFlightMap\([^)]+\);",
        r"'\s*flightMapSection\s*'",
        r"traveliaMap",
        r"tt-flight-map",
        r"flightMapSection",
        r"mapSection",
    ]
    
    # Remove JavaScript function definitions
    js_functions = [
        r"function renderFlightMap\(.*?// ── Price Calendar",
        r"// ── Flight Route Map Rendering.*?function \(.*?\)",
    ]
    
    # Remove the travel map section
    travel_map_start = r"\/\* Travel Map Section \*\/.*?@push\('scripts'\)"
    travel_map_end = r"@endpush"
    
    # Clean up the content
    for pattern in flight_map_sections:
        content = re.sub(pattern, "", content, flags=re.DOTALL)
    
    for pattern in route_visualization_patterns:
        content = re.sub(pattern, "", content)
    
    for pattern in map_patterns:
        content = re.sub(pattern, "", content)
    
    for pattern in js_functions:
        content = re.sub(pattern, "", content, flags=re.DOTALL)
    
    # Remove travel map section
    travel_map_full_pattern = r"\/\* Travel Map Section \*\/.*?@push\('scripts'\)"
    content = re.sub(travel_map_full_pattern, "@push('scripts')", content, flags=re.DOTALL)
    
    # Remove any empty lines left behind
    lines = content.splitlines(keepends=True)
    filtered_lines = []
    for i, line in enumerate(lines):
        if line.strip() == "":
            if i == 0 or i == len(lines) - 1:
                filtered_lines.append(line)
            elif not (lines[i-1].strip() == "" and lines[i+1].strip() == ""):
                filtered_lines.append(line)
        else:
            filtered_lines.append(line)
    
    content = "".join(filtered_lines)
    
    final_length = len(content)
    
    if final_length < initial_length:
        with open(main_view_path, "w") as f:
            f.write(content)
        
        removed_amount = initial_length - final_length
        print(f"✓ Cleaned flights.blade.php - removed {removed_amount} characters")
    else:
        print("✗ No changes made to flights.blade.php")

def check_remaining_references():
    """Check for any remaining references to flight route map functionality"""
    print("\nChecking for remaining references...")
    
    patterns = [
        "Route Visualisation",
        "traveliaMap",
        "tt-flight-map",
        "renderFlightMap",
        "flightMapSection",
        "flightRouteMap",
        "tt-section-light",
        "mapSection",
    ]
    
    for pattern in patterns:
        # Check all file types
        for ext in ["*.php", "*.blade.php", "*.js", "*.ts", "*.css"]:
            import glob
            for file_path in glob.glob(f"**/{ext}", recursive=True):
                try:
                    with open(file_path, "r") as f:
                        content = f.read()
                        if pattern in content:
                            print(f"⚠ Found '{pattern}' in: {file_path}")
                except:
                    pass

if __name__ == "__main__":
    print("Removing Flight Route functionality...")
    print("=" * 60)
    
    remove_component_files()
    clean_flights_view()
    
    print("=" * 60)
    check_remaining_references()
    
    print("\nFlight route map functionality has been removed!")