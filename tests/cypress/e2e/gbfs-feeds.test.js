// E2E coverage for the cb-vehicles additions to the CommonsBooking GBFS feeds:
// StationStatus, VehicleStatus and VehicleAvailability. The fixtures come from
// the CommonsBooking content-example.xml, extended with cb-vehicles postmeta.
const vehicleTypesPath = "/wp-json/commonsbooking/v1/vehicle_types.json";
const vehicleStatusPath = "/wp-json/commonsbooking/v1/vehicle_status.json";
const vehicleAvailabilityPath =
  "/wp-json/commonsbooking/v1/vehicle_availability.json";
const stationStatusPath = "/wp-json/commonsbooking/v1/station_status.json";

const indexVehicleTypes = (vehicleTypes) =>
  vehicleTypes.reduce((result, vehicleType) => {
    result[vehicleType.vehicle_type_id] = vehicleType;
    return result;
  }, {});

const findLongJohnType = (vehicleTypes) =>
  vehicleTypes.find(
    (vehicleType) =>
      Array.isArray(vehicleType.make) &&
      vehicleType.make.some((entry) => entry.text === "Long-John")
  );

describe("CB Vehicles GBFS feed additions", () => {
  let vehicleTypesById;
  let longJohnType;

  before(() => {
    cy.setupGbfsFixtures();

    return cy.request(vehicleTypesPath).then((response) => {
      const vehicleTypes = response.body.data.vehicle_types;
      vehicleTypesById = indexVehicleTypes(vehicleTypes);
      longJohnType = findLongJohnType(vehicleTypes);
      expect(longJohnType, "Long-John fixture vehicle type").to.exist;
    });
  });

  it("adds vehicle_type_id and current_range_meters to vehicle_status", () => {
    cy.request(vehicleStatusPath).then((response) => {
      expect(response.status).to.eq(200);

      const vehicles = response.body.data.vehicles;
      expect(vehicles).to.be.an("array").and.not.be.empty;

      const longJohnVehicle = vehicles.find(
        (vehicle) => vehicle.vehicle_type_id === longJohnType.vehicle_type_id
      );
      expect(longJohnVehicle, "Long-John vehicle in vehicle_status").to.exist;
      expect(longJohnVehicle.current_range_meters).to.eq(45);

      for (const vehicle of vehicles) {
        expect(
          vehicle.vehicle_type_id,
          `vehicle_type_id of ${vehicle.vehicle_id}`
        ).to.eq(vehicle.vehicle_id);

        const vehicleType = vehicleTypesById[vehicle.vehicle_type_id];
        expect(vehicleType, `vehicle type ${vehicle.vehicle_type_id}`).to.exist;

        if (vehicleType.propulsion_type === "human") {
          expect(vehicle).not.to.have.property("current_range_meters");
        } else {
          expect(
            vehicle.current_range_meters,
            `current_range_meters of ${vehicle.vehicle_id}`
          ).to.eq(vehicleType.max_range_meters);
        }
      }
    });
  });

  it("adds vehicle_type_id to vehicle_availability", () => {
    cy.request(vehicleAvailabilityPath).then((response) => {
      expect(response.status).to.eq(200);

      const vehicles = response.body.data.vehicles;
      expect(vehicles).to.be.an("array").and.not.be.empty;

      for (const vehicle of vehicles) {
        expect(
          vehicle.vehicle_type_id,
          `vehicle_type_id of ${vehicle.vehicle_id}`
        ).to.eq(vehicle.vehicle_id);
        expect(vehicleTypesById[vehicle.vehicle_type_id]).to.exist;
        expect(vehicle.availabilities).to.be.an("array");
      }

      const longJohnVehicle = vehicles.find(
        (vehicle) => vehicle.vehicle_type_id === longJohnType.vehicle_type_id
      );
      expect(
        longJohnVehicle,
        "Long-John vehicle in vehicle_availability"
      ).to.exist;
      expect(longJohnVehicle.station_id).to.be.a("string").and.not.be.empty;
      expect(longJohnVehicle.availabilities).to.be.an("array").and.not.be.empty;
    });
  });

  it("uses per-item vehicle types in station_status", () => {
    cy.request(vehicleAvailabilityPath).then((availabilityResponse) => {
      const longJohnVehicle = availabilityResponse.body.data.vehicles.find(
        (vehicle) => vehicle.vehicle_type_id === longJohnType.vehicle_type_id
      );
      expect(
        longJohnVehicle,
        "Long-John vehicle in vehicle_availability"
      ).to.exist;

      const longJohnStationId = longJohnVehicle.station_id;

      cy.request(stationStatusPath).then((response) => {
        expect(response.status).to.eq(200);

        const stations = response.body.data.stations;
        expect(stations).to.be.an("array").and.not.be.empty;

        for (const station of stations) {
          expect(
            station.vehicle_types_available,
            `vehicle_types_available of station ${station.station_id}`
          ).to.be.an("array");

          for (const available of station.vehicle_types_available) {
            expect(
              vehicleTypesById[available.vehicle_type_id],
              `vehicle type ${available.vehicle_type_id} at station ${station.station_id}`
            ).to.exist;
            expect(available.count).to.be.oneOf([0, 1]);
          }

          const availableTotal = station.vehicle_types_available.reduce(
            (total, available) => total + available.count,
            0
          );
          expect(
            availableTotal,
            `available vehicle total of station ${station.station_id}`
          ).to.eq(station.num_vehicles_available);
        }

        const longJohnStation = stations.find(
          (station) => station.station_id === longJohnStationId
        );
        expect(longJohnStation, `station ${longJohnStationId}`).to.exist;

        const longJohnAvailable =
          longJohnStation.vehicle_types_available.find(
            (available) =>
              available.vehicle_type_id === longJohnType.vehicle_type_id
          );
        expect(
          longJohnAvailable,
          `Long-John vehicle type at station ${longJohnStationId}`
        ).to.exist;
        expect(longJohnAvailable.count).to.be.oneOf([0, 1]);
      });
    });
  });

  it("advertises all GBFS feeds including vehicle_types", () => {
    cy.request("/wp-json/commonsbooking/v1/gbfs.json").then((response) => {
      expect(response.status).to.eq(200);

      const feedNames = response.body.data.feeds.map((feed) => feed.name);
      expect(feedNames).to.include.members([
        "station_status",
        "vehicle_availability",
        "vehicle_status",
        "vehicle_types",
      ]);
    });
  });
});
