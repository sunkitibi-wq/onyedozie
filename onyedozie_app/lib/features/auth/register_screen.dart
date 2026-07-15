import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../core/providers.dart';
import 'auth_provider.dart';

class RegisterScreen extends ConsumerStatefulWidget {
  const RegisterScreen({super.key});

  @override
  ConsumerState<RegisterScreen> createState() => _RegisterScreenState();
}

class _RegisterScreenState extends ConsumerState<RegisterScreen> {
  final _nameController = TextEditingController();
  final _emailController = TextEditingController();
  final _phoneController = TextEditingController();
  final _passwordController = TextEditingController();
  final _formKey = GlobalKey<FormState>();

  String? _selectedRole;
  List<String> _roles = [];

  List<dynamic> _lgas = [];
  List<dynamic> _wards = [];
  List<dynamic> _pollingUnits = [];
  int? _selectedLgaId;
  int? _selectedWardId;
  int? _selectedPuId;
  bool _isLoadingGeo = false;

  @override
  void initState() {
    super.initState();
    _loadRoles();
    _loadLgas();
  }

  Future<void> _loadRoles() async {
    setState(() {
      _isLoadingGeo = true;
    });
    try {
      final client = ref.read(apiClientProvider);
      final response = await client.get('/geography/roles');
      final data = response.data is Map ? response.data : <String, dynamic>{};
      if (data['success'] == true && data['data'] is List) {
        final List<dynamic> rolesData = data['data'];
        setState(() {
          _roles = rolesData.map((e) => e['name'].toString()).toList();
        });
      }
    } catch (e) {
      debugPrint('[RegisterRoles] Error loading roles: $e');
    } finally {
      setState(() {
        _isLoadingGeo = false;
      });
    }
  }

  Future<void> _loadLgas() async {
    setState(() {
      _isLoadingGeo = true;
    });
    try {
      final client = ref.read(apiClientProvider);
      final response = await client.get('/geography/lgas');
      debugPrint('[RegisterLGA] Response type: ${response.data.runtimeType}');
      final data = response.data is Map ? response.data : <String, dynamic>{};
      if (data['success'] == true && data['data'] is List) {
        setState(() {
          _lgas = data['data'];
        });
        debugPrint('[RegisterLGA] Loaded ${_lgas.length} LGAs');
        if (_lgas.isNotEmpty) {
          debugPrint('[RegisterLGA] First item id type: ${_lgas.first['id'].runtimeType}, value: ${_lgas.first['id']}');
        }
      }
    } catch (e) {
      debugPrint('[RegisterLGA] Error loading LGAs: $e');
    } finally {
      setState(() {
        _isLoadingGeo = false;
      });
    }
  }

  Future<void> _loadWards(int lgaId) async {
    setState(() {
      _isLoadingGeo = true;
      _wards = [];
      _pollingUnits = [];
      _selectedWardId = null;
      _selectedPuId = null;
    });
    try {
      final client = ref.read(apiClientProvider);
      final response = await client.get('/geography/wards?lga_id=$lgaId');
      if (response.data['success'] == true) {
        setState(() {
          _wards = response.data['data'];
        });
      }
    } catch (e) {
      // silent fail
    } finally {
      setState(() {
        _isLoadingGeo = false;
      });
    }
  }

  Future<void> _loadPollingUnits(int wardId) async {
    setState(() {
      _isLoadingGeo = true;
      _pollingUnits = [];
      _selectedPuId = null;
    });
    try {
      final client = ref.read(apiClientProvider);
      final response = await client.get('/geography/polling-units?ward_id=$wardId');
      if (response.data['success'] == true) {
        setState(() {
          _pollingUnits = response.data['data'];
        });
      }
    } catch (e) {
      // silent fail
    } finally {
      setState(() {
        _isLoadingGeo = false;
      });
    }
  }

  void _submit() {
    if (_formKey.currentState!.validate()) {
      ref.read(authProvider.notifier).register(
            name: _nameController.text.trim(),
            email: _emailController.text.trim(),
            phone: _phoneController.text.trim(),
            password: _passwordController.text.trim(),
            role: _selectedRole ?? '',
            lgaId: _selectedLgaId,
            wardId: _selectedRole == 'LGA Coordinator' ? null : _selectedWardId,
            puId: (_selectedRole == 'Polling Unit Coordinator' || _selectedRole == 'Volunteer') ? _selectedPuId : null,
          );
      // If success, user authenticated, main.dart switches screen automatically
    }
  }

  @override
  Widget build(BuildContext context) {
    final authState = ref.watch(authProvider);

    final List<DropdownMenuItem<int>> lgaItems = [];
    final Set<int> uniqueLgaIds = {};
    for (var lga in _lgas) {
      if (lga['id'] != null) {
        final id = lga['id'] is int ? lga['id'] as int : int.tryParse(lga['id'].toString()) ?? 0;
        if (!uniqueLgaIds.contains(id)) {
          uniqueLgaIds.add(id);
          lgaItems.add(DropdownMenuItem<int>(
            value: id,
            child: Text(lga['name']?.toString() ?? ''),
          ));
        }
      }
    }

    final List<DropdownMenuItem<int>> wardItems = [];
    final Set<int> uniqueWardIds = {};
    for (var ward in _wards) {
      if (ward['id'] != null) {
        final id = ward['id'] is int ? ward['id'] as int : int.tryParse(ward['id'].toString()) ?? 0;
        if (!uniqueWardIds.contains(id)) {
          uniqueWardIds.add(id);
          wardItems.add(DropdownMenuItem<int>(
            value: id,
            child: Text(ward['name']?.toString() ?? ''),
          ));
        }
      }
    }

    final List<DropdownMenuItem<int>> puItems = [];
    final Set<int> uniquePuIds = {};
    for (var pu in _pollingUnits) {
      if (pu['id'] != null) {
        final id = pu['id'] is int ? pu['id'] as int : int.tryParse(pu['id'].toString()) ?? 0;
        if (!uniquePuIds.contains(id)) {
          uniquePuIds.add(id);
          puItems.add(DropdownMenuItem<int>(
            value: id,
            child: Text(
              '${pu['code'] ?? ''} — ${pu['name'] ?? ''}',
              overflow: TextOverflow.ellipsis,
            ),
          ));
        }
      }
    }

    ref.listen(authProvider, (previous, next) {
      if (next.status == AuthStatus.error && next.errorMessage != null) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(next.errorMessage!),
            backgroundColor: Colors.red,
          ),
        );
      } else if (next.status == AuthStatus.needsVerification || next.status == AuthStatus.authenticated) {
        Navigator.of(context).pop();
      }
    });

    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        title: const Text('Campaign Registration'),
        backgroundColor: const Color(0xFF4361EE),
        foregroundColor: Colors.white,
      ),
      body: Center(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(24.0),
          child: Form(
            key: _formKey,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.stretch,
              children: [
                const Text(
                  'Join Onyendozi Connect',
                  textAlign: TextAlign.center,
                  style: TextStyle(
                    fontSize: 24,
                    fontWeight: FontWeight.bold,
                    color: Color(0xFF4361EE),
                  ),
                ),
                const SizedBox(height: 8),
                const Text(
                  'Create an account to track canvassing and reporting activities.',
                  textAlign: TextAlign.center,
                  style: TextStyle(fontSize: 12, color: Colors.grey),
                ),
                const SizedBox(height: 32),
                TextFormField(
                  controller: _nameController,
                  decoration: const InputDecoration(
                    labelText: 'Full Name',
                    prefixIcon: Icon(Icons.person),
                    border: OutlineInputBorder(),
                  ),
                  validator: (value) {
                    if (value == null || value.isEmpty) {
                      return 'Please enter your full name';
                    }
                    return null;
                  },
                ),
                const SizedBox(height: 16),
                TextFormField(
                  controller: _emailController,
                  decoration: const InputDecoration(
                    labelText: 'Email Address',
                    prefixIcon: Icon(Icons.email),
                    border: OutlineInputBorder(),
                  ),
                  keyboardType: TextInputType.emailAddress,
                  validator: (value) {
                    if (value == null || value.isEmpty) {
                      return 'Please enter your email address';
                    }
                    if (!RegExp(r'^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$').hasMatch(value)) {
                      return 'Please enter a valid email address';
                    }
                    return null;
                  },
                ),
                const SizedBox(height: 16),
                TextFormField(
                  controller: _phoneController,
                  decoration: const InputDecoration(
                    labelText: 'Phone Number',
                    prefixIcon: Icon(Icons.phone),
                    border: OutlineInputBorder(),
                  ),
                  keyboardType: TextInputType.phone,
                  validator: (value) {
                    if (value == null || value.isEmpty) {
                      return 'Please enter your phone number';
                    }
                    return null;
                  },
                ),
                const SizedBox(height: 16),
                TextFormField(
                  controller: _passwordController,
                  decoration: const InputDecoration(
                    labelText: 'Password',
                    prefixIcon: Icon(Icons.lock),
                    border: OutlineInputBorder(),
                  ),
                  obscureText: true,
                  validator: (value) {
                    if (value == null || value.length < 8) {
                      return 'Password must be at least 8 characters';
                    }
                    return null;
                  },
                ),
                const SizedBox(height: 16),
                DropdownButtonFormField<String>(
                  value: _roles.contains(_selectedRole) ? _selectedRole : null,
                  decoration: const InputDecoration(
                    labelText: 'Campaign Role',
                    prefixIcon: Icon(Icons.badge),
                    border: OutlineInputBorder(),
                  ),
                  items: _roles.map((role) {
                    return DropdownMenuItem(
                      value: role,
                      child: Text(role),
                    );
                  }).toList(),
                  validator: (value) => value == null ? 'Please select a Role' : null,
                  onChanged: (val) {
                    if (val != null) {
                      setState(() {
                        _selectedRole = val;
                        // Clear lower hierarchy if role restricts it
                        if (val == 'LGA Coordinator') {
                          _selectedWardId = null;
                          _selectedPuId = null;
                          _wards = [];
                          _pollingUnits = [];
                        } else if (val == 'Ward Coordinator') {
                          _selectedPuId = null;
                          _pollingUnits = [];
                        }
                      });
                    }
                  },
                ),
                const SizedBox(height: 16),
                DropdownButtonFormField<int>(
                  value: uniqueLgaIds.contains(_selectedLgaId) ? _selectedLgaId : null,
                  decoration: const InputDecoration(
                    labelText: 'Local Government Area (LGA)',
                    prefixIcon: Icon(Icons.location_city),
                    border: OutlineInputBorder(),
                  ),
                  items: lgaItems,
                  validator: (value) => value == null ? 'Please select an LGA' : null,
                  onChanged: (val) {
                    if (val != null) {
                      setState(() {
                        _selectedLgaId = val;
                      });
                      _loadWards(val);
                    }
                  },
                ),
                if (_selectedRole != 'LGA Coordinator') ...[
                  const SizedBox(height: 16),
                  DropdownButtonFormField<int>(
                    value: uniqueWardIds.contains(_selectedWardId) ? _selectedWardId : null,
                    decoration: const InputDecoration(
                      labelText: 'Ward',
                      prefixIcon: Icon(Icons.map),
                      border: OutlineInputBorder(),
                    ),
                    items: wardItems,
                    validator: (value) {
                      if (_selectedRole != 'LGA Coordinator' && value == null) {
                        return 'Please select a Ward';
                      }
                      return null;
                    },
                    onChanged: (val) {
                      if (val != null) {
                        setState(() {
                          _selectedWardId = val;
                        });
                        _loadPollingUnits(val);
                      }
                    },
                  ),
                ],
                if (_selectedRole == 'Polling Unit Coordinator' || _selectedRole == 'Volunteer') ...[
                  const SizedBox(height: 16),
                  DropdownButtonFormField<int>(
                    value: uniquePuIds.contains(_selectedPuId) ? _selectedPuId : null,
                    decoration: const InputDecoration(
                      labelText: 'Polling Unit (PU)',
                      prefixIcon: Icon(Icons.how_to_vote),
                      border: OutlineInputBorder(),
                    ),
                    items: puItems,
                    validator: (value) {
                      if (_selectedRole == 'Polling Unit Coordinator' && value == null) {
                        return 'Please select a Polling Unit';
                      }
                      return null;
                    },
                    onChanged: (val) {
                      setState(() {
                        _selectedPuId = val;
                      });
                    },
                  ),
                ],
                const SizedBox(height: 32),
                ElevatedButton(
                  onPressed: (authState.status == AuthStatus.loading || _isLoadingGeo) ? null : _submit,
                  style: ElevatedButton.styleFrom(
                    backgroundColor: const Color(0xFF4361EE),
                    padding: const EdgeInsets.symmetric(vertical: 16),
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(8),
                    ),
                  ),
                  child: (authState.status == AuthStatus.loading || _isLoadingGeo)
                      ? const CircularProgressIndicator(color: Colors.white)
                      : const Text(
                          'Register Now',
                          style: TextStyle(fontSize: 16, color: Colors.white, fontWeight: FontWeight.bold),
                        ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
