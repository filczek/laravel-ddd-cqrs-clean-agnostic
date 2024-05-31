## Laravel with DDD, CQRS & Hexagonal Architecture

Welcome to a Laravel project that showcases the power of Domain-Driven Design (DDD), Command Query Responsibility Segregation (CQRS), and Hexagonal Architecture principles.

This project aims to help you understand these concepts and how they can be applied in a real-world scenario.

## ⚠️ WORK IN PROGRESS ⚠️

**Note**: This project is still in its early stages of development, and everything is subject to change.

However, feel free to explore the code in the [/src](src) directory, especially the [Post module](src/Post), which is already working and showcases the principles and concepts this project aims to demonstrate.

## Project Structure

This project follows a "modular monolith" approach, where each module is organized into the following folders:

### Application (Use Cases & DTOs)

Think of this layer as the "interface" between the outside world and our business logic. It contains:

- **Use Cases**: Actions that can be performed in our service, such as "Create User" or "Place Order". These actions follow the CQRS pattern, which means they can be either **Commands** (e.g., "Create User") or **Queries** (e.g., "Get User List").
- **Data Transfer Objects (DTOs)**: Simple objects that carry data between the application layers.

### Configuration

Configuration Layer contains all framework-specific configuration such as:

- Dependency Injection
- Event listening
- Route configuration

### Domain (Business Logic & Rules)

This is the heart of our application, where the business logic and rules are defined. The Domain layer contains:

- **Entities/Aggregates**: Objects that represent the business domain, such as a User or an Order.
- **Value Objects**: Immutable objects that have a set of values, such as an Address or a Money object.
- **Domain Events**: Events that occur within the business domain, such as "UserCreated" or "OrderPlaced".

### Infrastructure (Data Access)

This layer provides the necessary data to our Domain layer. It's responsible for:

- Storing and retrieving data from databases, Redis, RabbitMQ, and other storage systems.
- Acting as the "plumbing" that connects our Domain layer to the outside world.

### Interface (Entry Points)

This layer contains the entry points for external systems to interact with our module. It's where:

- **Controllers** are defined to handle requests and return responses.
- **APIs** are exposed to interact with our module.

For example, if you need to expose a REST API, you would create a controller in this layer to handle API requests.

## In a Nutshell

- The **Application** layer defines the actions that can be performed in our service.
- The **Domain** layer contains the business logic and rules.
- The **Infrastructure** layer provides data to the Domain layer.
- The **Interface** layer exposes our module to the outside world.

By following these principles, you've created a scalable, maintainable, and flexible architecture that's easy to understand and extend.
